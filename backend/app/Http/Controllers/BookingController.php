<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
 public function book(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'phone' => 'required|string',
            'national_id' => 'required|string',
            'nationality' => 'required|string',
            'ticket_type' => 'required|in:entry,guided',
            'payment_method' => 'required|in:pay_now,pay_museum',
            'event_id' => 'required|integer|exists:events,id',
            'user_id' => 'required|integer|exists:users,id',
         
        ]);

        $booking = Booking::create($validated);

       

           $calculatedAmount = $this->calculateAmount($request->event_id);
            $booking->amount=$calculatedAmount;

        if ($validated['payment_method'] === 'pay_now') {
            return response()->json([
                'next_step' => 'select_payment_method',
                'booking_id' => $booking->id
            ]);
        }

        return response()->json(['message' => 'Booking successful, pay at museum']);
    }



    //function for calculate every event amount based on types
        private function calculateAmount($eventId)
    {
        // You can replace this logic with actual event-based pricing
        return 200; // Fixed amount for example
    }
}
