<?php

namespace App\Http\Controllers;

use App\Model\Booking;
use App\Model\BookedRoom;


class BookingController extends Controller
{
    public function index()
    {
        $booking = Booking::all();
        if ($booking->isNotEmpty()) {
            $booking->map(function ($booking){
                $booking->Customer;
                $booking->BookedRoom;
            });
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
            'room_category_id' => 'required',
            'number_of_rooms' => 'required',
            'number_of_adult' => 'required',
            'number_of_child' => 'required',
            'check_in_date' => 'required',
            'check_out_date' => 'required',
        ]);
    }

    public function getBookedRoom()
    {
        $booking = Booking::all();
        if ($booking->isNotEmpty()) {
            $booking->map(function ($booking){
                $booking->Customer;
                $booking->RoomCategory;

            });
            return response()->json([
                'success' => true,
                'message' => 'Lists of BookedRooms.',
                'data' => $booking
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Currently, there is no any BookedRooms yet.',
            ]);
        }
    }

    //Store for booked_room table
    // public function storeBookedRoom()
    // {
    //     $bookedRoom = BookedRoom::create($this->validateBookedRoomRequest());
    //     return response()->json([
    //         'success' => true,
    //         'message' => 'BookedRoom has been created successfully.',
    //         'data' => $bookedRoom
    //     ]);
    // }

    // public function showBookedRoom(BookedRoom $bookedRoom)
    // {
    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Data of an individual bookedRoom',
    //         'data' => $bookedRoom
    //     ]);
    // }

    // public function validateBookedRoomRequest()
    // {
    //     return request()->validate([
    //         'room_category_id' => 'required',
    //         'booking_id' => 'required',
    //         'number_of_rooms' => 'required'
    //     ]);
    // }

}
