<?php

namespace App\Http\Controllers;

use App\Jobs\CancelBooking;
use App\Mail\BookingMail;
use App\Model\Booking;
use App\Model\Customer;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class BookingController extends Controller
{
    public function index()
    {
        $booking = Booking::all();
        if ($booking->isNotEmpty()) {
            $booking->map(function ($booking) {
                $booking->Customer;
                $booking->BookedRoom;
            });
            return $this->jsonResponse(true, 'Lists of Bookings.', $booking);
        } else {
            return $this->jsonResponse(false, 'Currently, there is no any Bookings yet.');
        }
    }

    public function store()
    {
        // request()->check_in_date = date('Y-m-d h:i:s', strtotime(request()->check_in_date));
        // request()->check_out_date = date('Y-m-d h:i:s', strtotime(request()->check_out_date));
        // return request();

        $message = '';
        $booking = Booking::create($this->validateRequest());
        $userEmail = $booking->customer->email;
        if ($booking && $userEmail) {
            Mail::to($userEmail)->send(new BookingMail($booking->check_in_date, $booking->check_out_date));
            $job = (new CancelBooking($booking->id))->delay(30);
            // Carbon::now()->addHour(12)
            $this->dispatch($job);
            $message = 'Booking has been created successfully.';
        } else if ($booking) {
            $message = 'Booking has been created successfully. User does not have email address.';
        } else {
            $message = 'Booking failed';
        }
        return $this->jsonResponse(true, $message, $booking);
    }

    public function show(Booking $booking)
    {
        return $this->jsonResponse(true, 'Data of an individual Booking.', $booking);
    }

    public function update(Booking $booking)
    {
        $booking->update($this->validateRequest());
        return $this->jsonResponse(true, 'Booking has been updated.', $booking);
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();
        return $this->jsonResponse(true, 'Booking has been deleted successfully.');
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
            $booking->map(function ($booking) {
                $booking->Customer;
                $booking->RoomCategory;
            });
            return $this->jsonResponse(true, 'Lists of BookedRooms.', $booking);
        } else {
            return $this->jsonResponse(false, 'Currently, there is no any BookedRooms yet.', $booking);
        }
    }

    private function jsonResponse($success = false, $message = '', $data = null)
    {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data' => $data
        ]);
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
