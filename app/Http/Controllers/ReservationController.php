<?php

namespace App\Http\Controllers;

use App\Model\Reservation;
use Symfony\Component\HttpFoundation\Response;

class ReservationController extends Controller
{
    public function index()
    {
        $reservation = Reservation::all();
        if ($reservation->isNotEmpty()) {
            $reservation->map(function ($reservation) {
                // These three data may not be required when Room Availability is implemented
                $reservation->Room;
                $reservation->Room->RoomCategory;
                $reservation->Customer;
                $reservation->Booking;
                // ------------------------------------
            });
            return response()->json([
                'success' => true,
                'message' => 'Lists of Reservation.',
                'data' => $reservation
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Currently, there is no any Reservation yet.',
            ]);
        }
    }

    public function store()
    {
        $reservation = Reservation::create($this->validateRequest());
        return response()->json([
            'success' => true,
            'message' => 'Reservation has been created successfully.',
            'data' => $reservation
        ]);
    }

    public function show(Reservation $reservation)
    {
        $reservation->Room;
        $reservation->Customer;
        return response()->json([
            'success' => true,
            'message' => 'Data of an individual Reservation',
            'data' => $reservation
        ]);
    }

    public function update(Reservation $reservation)
    {
        $reservation->update($this->validateRequest());
        return response()->json([
            'success' => true,
            'message' => 'Reservation has been updated',
            'data' => $reservation
        ]);
    }

    public function destroy(Reservation $reservation)
    {
        $reservation->delete();
        return response()->json([
            'success' => true,
            'message' => 'Reservation has been deleted successfully.'
        ]);
    }

    private function validateRequest()
    {
        return request()->validate([
            'room_id' => 'required',
            'customer_id' => 'required',
            'booking_id' => 'nullable',
            'check_in_date' => 'required',
            'check_out_date' => 'required',

        ]);
    }
}
