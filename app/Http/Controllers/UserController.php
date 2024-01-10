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
        $validated = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string']
        ]);

        $dbUser = User::where('username', $validated['username'])->first();

        if ($dbUser) {
            if (Hash::check($validated['password'], $dbUser->password)) {
                return response()->json();
            } else {
                return response()->json(['error' => 'Invalid credentials'], 404);
            }
        } else {
            return response()->json(['error' => 'Invalid credentials'], 404);
        }
    }

    public function getRegisteredEvents(int $userId): JsonResponse
    {
        //TODO: get user through token
        $dbUser = User::find($userId);

        if ($dbUser) {
            $registeredEventList = DB::table('event_user')->where('user_id', $userId)->pluck('event_id');

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
        $validated = $request->validate([
            'userId' => ['required', 'numeric'],
            'eventId' => ['required', 'numeric']
        ]);

        $dbUser = User::find($validated['userId']);

        if ($dbUser) {
            $dbUser->events()->attach($validated['eventId']);

            return response()->json(status: 201);
        } else {
            return response()->json(['error' => 'User not found'], 404);
        }

    }

}
