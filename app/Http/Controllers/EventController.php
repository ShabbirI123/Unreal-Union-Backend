<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{
    /**
     * Create new event
     */
    public function createEvent(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'unique:event,name'],
            'description' => ['required', 'string'],
            'location' => ['required', 'string'],
            'date' => ['required', 'date']
        ], $messages = [
            'name.unique' => 'An event with this name already exists! Please choose a different name.'
        ]);

        if ($validator->fails()) {
            return response()->json([$validator->errors()], 406);
        }

        $validated = $validator->safe()->all();

        try {
            $event = new Event;

            $event->name = $validated['name'];
            $event->description = $validated['description'];
            $event->location = $validated['location'];
            $event->date = Carbon::parse($validated['date']);

            $event->save();

            return response()->json(status: 201);
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }

    /**
     * Get all events of which the name matches with the searchString.
     * If no searchString is provided, all events will be returned
     */
    public function getEvents(?string $searchString = null): JsonResponse
    {
        $dbEvent = $searchString != null ? Event::where('name', 'like', "%{$searchString}%")->get() : Event::all();

        if ($dbEvent->isNotEmpty()) {
            $data = $dbEvent->map(function (Event $event) {
                return [
                    'name' => $event->name,
                    'description' => $event->description,
                    'location' => $event->location,
                    'date' => $event->date
                ];
            });

            return response()->json(['data' => $data]);
        } else {
            return response()->json(['error' => 'No event was found'], 404);
        }
    }
}
