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
            'name' => ['required', 'string'],
            'description' => ['required', 'string'],
            'location' => ['required', 'string'],
            'date' => ['required', 'date']
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
}
