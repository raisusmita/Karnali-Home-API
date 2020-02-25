<?php

namespace App\Http\Controllers;

use App\Model\Booking;

class BookingController extends Controller
{
    public function index()
    {
        $booking = Booking::all();
        if ($booking->isNotEmpty()) {
            return response()->json([
                'success' => true,
                'message' => 'Lists of Bookings.',
                'data' => $booking
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Currently, there is no any Bookings yet.',
            ]);
        }
    }

    public function store()
    {
        $booking = Booking::create($this->validateRequest());
        return response()->json([
            'success' => true,
            'message' => 'Booking has been created successfully.',
            'data' => $booking
        ]);
    }

    public function show(Booking $booking)
    {
        return response()->json([
            'success' => true,
            'message' => 'Data of an individual Booking',
            'data' => $booking
        ]);
    }

    public function update(Booking $booking)
    {
        $booking->update($this->validateRequest());
        return response()->json([
            'success' => true,
            'message' => 'Booking has been updated',
            'data' => $booking
        ]);
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();
        return response()->json([
            'success' => true,
            'message' => 'Booking has been deleted successfully.'
        ]);
    }

    public function validateRequest()
    {
        return request()->validate([
            'customer_id' => 'required',
            'number_of_customers' => 'required',
            'check_in_date' => 'required',
            'check_out_date' => 'required',
        ]);
    }
}
