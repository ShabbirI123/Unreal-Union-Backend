<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Rating;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\JsonResponse;

class RatingController extends Controller
{
    /**
     * Rate event
     */
    public function rateEvent(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'userId' => ['required', 'numeric'],
            'eventId' => ['required', 'numeric'],
            'rating' => ['required', 'numeric', 'between:1,10']
        ]);

        if ($validator->fails()) {
            return response()->json([$validator->errors()], 409);
        }

        $validated = $validator->safe()->all();

        $dbUser = User::find($validated['userId']);
        $dbEvent = Event::find($validated['eventId']);

        if ($dbUser && $dbEvent) {

            $dbRatings = Rating::where('user_id', $validated['userId'])
                ->where('event_id', $validated['eventId']);

            if ($dbRatings->exists()) {
                $dbRatings->update(['rating' => $validated['rating']]);
                return response()->json(['data' => 'Updated your rating']);
            }

            try {
                $rating = new Rating();
                $rating->rating = $validated['rating'];

                $rating->users()->associate($dbUser);
                $rating->events()->associate($dbEvent);
                $dbEvent->ratings()->save($rating);

                $rating->save();

                return response()->json(status: 201);
            } catch (Exception $exception) {
                error_log($exception);
                return response()->json(['error' => $exception->getMessage()], 500);
            }
        } else {
            return response()->json(['error' => 'User or event not found'], 404);
        }
    }
}
