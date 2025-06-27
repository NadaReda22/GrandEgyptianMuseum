<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
  // Get all events
  public function index()
  {
      $events = Event::all();

      return response()->json([
          'message' => 'All events retrieved successfully',
          'data' => $events,
      ], 200);
  }

  // Show a specific event
  public function show($id)
  {
      $event = Event::findOrFail($id);

      return response()->json([
          'message' => 'Event retrieved successfully',
          'data' => $event,
      ], 200);
  }

  // Store a new event
  public function store(Request $request)
  {

    $request['start_date']=Carbon::parse($request['start_date'])->format("Y-m-d");
    if(asset( $request['end_date']))
    {
      $request['end_date']=Carbon::parse($request['end_date'])->format("Y-m-d");
    }

      $validated = $request->validate([
          'title' => 'required|string|max:255',
          'description' => 'nullable|string',
          'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
          'start_date' => 'required|date',
          'end_date' => 'nullable|date|after_or_equal:start_date',
          'location' => 'required|string|max:255',
      ]);


   
   $event = new Event($validated);

         if ($request->hasFile('image')) {
                $file = $request->file('image');

                // Clean filename and make it unique
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $file->getClientOriginalExtension();
                $fileName = $originalName . '_' . time() . '.' . $extension;

                // Store the file in storage/app/public/uploads/events
                $file->storeAs('uploads/events', $fileName, 'public');

                // Save the relative path for accessing via asset('storage/' . $event->image)
                $event->image = 'uploads/events/' . $fileName;
            }


      $event->save();

      return response()->json([
          'message' => 'Event created successfully',
          'data' => $event,
      ], 201);
  }

  // Update an existing event
  public function update(Request $request, $id)
  {
      $event = Event::findOrFail($id);

      $validated = $request->validate([
          'title' => 'required|string|max:255',
          'description' => 'nullable|string',
          'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
          'start_date' => 'required|date',
          'end_date' => 'nullable|date|after_or_equal:start_date',
          'location' => 'required|string|max:255',
      ]);

      // Delete old image if new one uploaded
        //Handle image file
              if ($request->hasFile('image')) {
                // Delete old image if it exists
                if (!empty($event->image) && Storage::disk('public')->exists($event->image)) {
                    Storage::disk('public')->delete($event->image);
                }

                // Get uploaded file
                $file = $request->file('image');

                // Clean filename and make it unique
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $file->getClientOriginalExtension();
                $fileName = $originalName . '_' . time() . '.' . $extension;

                // Store file using the public disk (in storage/app/public/uploads/events)
                $file->storeAs('uploads/events', $fileName, 'public');

                // Save the relative path to the database
                $validatedData['image'] = 'uploads/events/' . $fileName;
            }
            

      $event->update($validated);

      return response()->json([
          'message' => 'Event updated successfully',
          'data' => $event,
      ], 200);
  }

  // Delete an event
  public function destroy($id)
  {
      $event = Event::findOrFail($id);

      if (!empty($event->image) && file_exists(public_path($event->image))) {
          unlink(public_path($event->image));
      }

      $event->delete();

      return response()->json([
          'message' => 'Event deleted successfully',
          'data' => $event,
      ], 200);
  }
}
