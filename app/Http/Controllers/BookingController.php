<?php

namespace App\Http\Controllers;

use App\Model\Booking;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


class BookingController extends Controller
{
    public function index()
    {
        $booking = Booking::all();
        if ($booking->isNotEmpty()) {
            return response([
                'success' => true,
                'message' => 'Lists of Bookings.',
                'data' => $booking
            ], Response::HTTP_CREATED);
        } else {
            return response([
                'success' => false,
                'message' => 'Currently, there is no any Bookings yet.',
            ], Response::HTTP_CREATED);
        }
    }

    public function store()
    {
        $booking = Booking::create($this->validateRequest());
        return response([
            'success' => true,
            'message' => 'Booking has been created successfully.',
            'data' => $booking
        ], Response::HTTP_CREATED);
    }

    public function show(Booking $booking)
    {
        return response([
            'success' => true,
            'message' => 'Data of an individual Booking',
            'data' => $booking
        ], Response::HTTP_CREATED);
    }

    public function update(Booking $booking)
    {
        $booking->update($this->validateRequest());
        return response([
            'success' => true,
            'message' => 'Booking has been updated',
            'data' => $booking
        ], Response::HTTP_CREATED);
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();
        return response([
            'success' => true,
            'message' => 'Booking has been deleted successfully.'
        ], Response::HTTP_NO_CONTENT);
    }

    public function validateRequest()
    {
        return request()->validate([
            'customer_id' => 'required',
            'booking_start_date' => 'required',
            'booking_end_date' => 'required',
            'no_of_customers' => 'required',
            'no_of_room' => 'required'
        ]);
    }
}
