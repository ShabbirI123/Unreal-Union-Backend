<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
            'date' => ['required', 'date'],
            'imagePath' => ['required', 'string'],
            'image' => ['required', 'image'],
            'category' => ['required', 'string']
        ], $messages = [
            'name.unique' => 'An event with this name already exists! Please choose a different name.'
        ]);

        if ($validator->fails()) {
            return response()->json([$validator->errors()], 406);
        }

        $validated = $validator->safe()->all();

        $imagePath = $request->file('image')->store('public/images');

        try {
            $event = new Event;

            $event->name = $validated['name'];
            $event->description = $validated['description'];
            $event->location = $validated['location'];
            $event->date = Carbon::parse($validated['date']);
            $event->image_path = $imagePath;
            $event->category = $validated['category'];

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
    public function getEventList(?string $searchString = null): JsonResponse
    {
        $dbEvent = $searchString != null ? Event::where('name', 'like', "%{$searchString}%")->get() : Event::all();

        if ($dbEvent->isNotEmpty()) {
            $data = $dbEvent->map(function (Event $event) {
                return [
                    'eventId' => $event->event_id,
                    'name' => $event->name,
                    'description' => $event->description,
                    'location' => $event->location,
                    'date' => $event->date,
                    'imagePath' => Storage::url($event->image_path),
                    'category' => $event->category,
                ];
            });

            return response()->json(['data' => $data]);
        } else {
            return response()->json(['error' => 'No event was found'], 404);
        }
    }

    public function getEvent(int $eventId): JsonResponse
    {
        $dbEvent = Event::where('event_id', $eventId)->first();

        if ($dbEvent) {
            $imagePage = env('APP_URL') . ':8000' . Storage::url($dbEvent->image_path);

            $data = [
                'name' => $dbEvent->name,
                'description' => $dbEvent->description,
                'location' => $dbEvent->location,
                'date' => $dbEvent->date,
                'imagePath' => $imagePage,
                'category' => $dbEvent->category
            ];

            return response()->json(['data' => $data]);
        } else {
            return response()->json(['error' => 'No event was found'], 404);
        }
    }
}
