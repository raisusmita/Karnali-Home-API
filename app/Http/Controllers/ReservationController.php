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
            return $this->jsonResponse(true, 'Lists of Reservation.', $reservation);
        } else {
            return $this->jsonResponse(false, 'Currently, there is no any Reservation yet.');
        }
    }

    public function store()
    {
        $reservation = Reservation::create($this->validateRequest());
        return $this->jsonResponse(true, 'Reservation has been created successfully.', $reservation);
    }

    public function show(Reservation $reservation)
    {
        $reservation->Room;
        $reservation->Customer;
        return $this->jsonResponse(true, 'Data of an individual Reservation.', $reservation);
    }

    public function update(Reservation $reservation)
    {
        $reservation->update($this->validateRequest());
        return $this->jsonResponse(true, 'Reservation has been updated.', $reservation);
    }

    public function destroy(Reservation $reservation)
    {
        $reservation->delete();
        return $this->jsonResponse(true, 'Reservation has been deleted successfully.');
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

    private function jsonResponse($success = false, $message = '', $data = null)
    {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data' => $data
        ]);
    }
}
