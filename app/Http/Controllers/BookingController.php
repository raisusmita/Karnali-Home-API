<?php

namespace App\Http\Controllers;

use App\Model\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        // Show all booking  detail
        $booking = ['booking' => Booking::all()];
        return $booking;
    }

    public function store(Request $request)
    {
        // Store booking
        $booking = Booking::create($this->validateRequest());
        return $booking;
    }

    public function show(Booking $booking)
    {
        // show individual booking
        return $booking;
    }

    public function update(Booking $booking)
    {
        //Update booking
        $booking->update($this->validateRequest());
        return $booking;

    }

    public function destroy(Booking $booking)
    {
        //Delete Booking
        $booking->delete();
        return 'Booking  Deleted Successfully';

    }

    // Form validation function
    public function validateRequest()
    {
        return request()->validate([
            'customer_id'=> 'required',
            'booking_start_date' => 'required',
            'booking_end_date' => 'required',
            'no_of_customers' => 'required',
            'no_of_room'=> 'required',
        ]);
    }
}
