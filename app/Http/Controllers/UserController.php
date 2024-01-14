<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Register new user
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'username' => ['required', 'string', 'unique:user,username'],
            'password' => ['required', 'string']
        ], $messages = [
            'username.unique' => 'This username is already taken!'
        ]);

        if ($validator->fails()) {
            return response()->json([$validator->errors()], 409);
        }

        $validated = $validator->safe()->all();

        try {
            $user = new User;

            $user->username = $validated['username'];
            $user->password = Hash::make($validated['password']);

            $user->save();

            return response()->json(status: 201);
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }

    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'username' => ['required', 'string'],
            'password' => ['required', 'string']
        ]);

        if ($validator->fails()) {
            return response()->json([$validator->errors()], 400);
        }

        $validated = $validator->safe()->all();

        $dbUser = User::where('username', $validated['username'])->first();

        if ($dbUser) {
            if (Hash::check($validated['password'], $dbUser->password)) {

                try {
                    $dbUser->tokens()->delete();
                } catch (Exception $exception) {
                    return response()->json(['error' => $exception->getMessage()], 500);
                }

                $token = $dbUser->createToken('authToken')->plainTextToken;

                return response()->json(['id' => $dbUser->user_id, 'apiToken' => $token]);
            } else {
                return response()->json(['error' => 'Invalid credentials'], 404);
            }
        } else {
            return response()->json(['error' => 'Invalid credentials'], 404);
        }
    }

    public function getRegisteredEvents(int $userId): JsonResponse
    {
        $dbUser = User::find($userId);

        if ($dbUser) {
            $registeredEventList = DB::table('event_user')
                ->where('user_id', $userId)
                ->pluck('event_id');

            if ($registeredEventList->isNotEmpty()) {
                $eventList = Event::whereIn('event_id', $registeredEventList)->get();

                $data = $eventList->map(function (Event $event) {
                    return [
                        'name' => $event->name,
                        'description' => $event->description,
                        'location' => $event->location,
                        'date' => $event->date
                    ];
                });

                return response()->json(['data' => $data]);
            } else {
                return response()->json(['error' => 'No events found for this user'], 404);
            }
        } else {
            return response()->json(['error' => 'User not found'], 404);
        }
    }

    public function registerForEvent(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'userId' => ['required', 'numeric'],
            'eventId' => ['required', 'numeric']
        ]);

        if ($validator->fails()) {
            return response()->json([$validator->errors()], 400);
        }

        $validated = $validator->safe()->all();

        $dbUser = User::find($validated['userId']);
        $dbEvent = Event::find($validated['eventId']);

        if ($dbUser) {
            if ($dbUser->events()->wherePivot('event_id', $validated['eventId'])->exists()) {
                return response()->json(['error' => 'User already registered for this event'], 400);
            }
            if ($dbEvent) {
                $participationLimit = $dbEvent->participation_limit;
                if ($participationLimit > 0) {
                    $dbEvent::where('event_id', $validated['eventId'])->update(['participation_limit' => $participationLimit - 1]);

                    $dbUser->events()->attach($validated['eventId']);
                } else {
                    return response()->json(['error' => "Event is full"], 403);
                }

                return response()->json(status: 201);
            } else {
                return response()->json(['error' => 'Event not found'], 404);
            }

        } else {
            return response()->json(['error' => 'User not found'], 404);
        }

    }

    public function unregisterFromEvent(int $userId, int $eventId): JsonResponse
    {
        $dbUser = User::find($userId);
        $dbEvent = Event::find($eventId);

        if ($dbUser) {
            try {
                if (!$dbUser->events()->wherePivot('event_id', $eventId)->exists()) {
                    return response()->json(['error' => 'User is not registered for this event'], 404);
                }

                $dbUser->events()->detach($eventId);

                $participationLimit = $dbEvent->participation_limit;
                $dbEvent::where('event_id', $eventId)->update(['participation_limit' => $participationLimit + 1]);

                return response()->json(status: 204);
            } catch (Exception $exception) {
                error_log($exception);
                return response()->json(['error' => $exception], 400);
            }
        } else {
            return response()->json(['error' => 'User not found'], 404);
        }
    }


    public function invalidToken(int $userId): JsonResponse
    {
        try {
            $dbUser = User::where('user_id', $userId)->first();
            $dbUser->tokens()->delete();
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }

        return response()->json(status: 204);
    }

}
