<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Carbon\Carbon;

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
          $fileName = time() . '.' . $request->image->extension();
          $request->image->move(public_path('uploads/events'), $fileName);
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
      if ($request->hasFile('image')) {
          if (!empty($event->image) && file_exists(public_path($event->image))) {
              unlink(public_path($event->image));
          }

          $fileName = time() . '.' . $request->image->extension();
          $request->image->move(public_path('uploads/events'), $fileName);
          $validated['image'] = 'uploads/events/' . $fileName;
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
