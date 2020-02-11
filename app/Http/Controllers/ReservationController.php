<?php

namespace App\Http\Controllers;

use App\Model\Reservation;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ReservationController extends Controller
{
    public function index()
    {
        $reservation = Reservation::all();
        if ($reservation->isNotEmpty()) {
            return response([
                'success' => true,
                'message' => 'Lists of Customers.',
                'data' => $reservation
            ], Response::HTTP_CREATED);
        } else {
            return response([
                'success' => false,
                'message' => 'Currently, there is no any Customers yet.',
            ], Response::HTTP_CREATED);
        }
    }

    public function store()
    {
        $reservation = Reservation::create($this->validateRequest());
        return response([
            'success' => true,
            'message' => 'Reservation has been created successfully.',
            'data' => $reservation
        ], Response::HTTP_CREATED);
    }

    public function show(Reservation $reservation)
    {
        return response([
            'success' => true,
            'message' => 'Data of an individual Reservation',
            'data' => $reservation
        ], Response::HTTP_CREATED);
    }

    public function update(Reservation $reservation)
    {
        $reservation->update($this->validateRequest());
        return response([
            'success' => true,
            'message' => 'Reservation has been updated',
            'data' => $reservation
        ], Response::HTTP_CREATED);
    }

    public function destroy(Reservation $reservation)
    {
        $reservation->delete();
        return response([
            'success' => true,
            'message' => 'Reservation has been deleted successfully.'
        ], Response::HTTP_NO_CONTENT);
    }

    private function validateRequest()
    {
        return request()->validate([
            'room_id' => 'required',
            'customer_id' => 'required',
            'check_in_date' => 'required',
            'check_out_date' => 'required',
            'availability' => 'required'

        ]);
    }
}
