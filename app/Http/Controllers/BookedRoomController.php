<?php

namespace App\Http\Controllers;

use App\Model\BookedRoom;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BookedRoomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bookedRoom = BookedRoom::all();
        if ($bookedRoom->isNotEmpty()) {
            return response([
                'success' => true,
                'message' => 'Lists of Booked Rooms.',
                'data' => $bookedRoom
            ], Response::HTTP_CREATED);
        } else {
            return response([
                'success' => false,
                'message' => 'Currently, there is no any Booked Room yet.',
            ], Response::HTTP_CREATED);
        }
    }

    public function store()
    {
        $bookedRoom = BookedRoom::create($this->validateRequest());
        return response([
            'success' => true,
            'message' => 'Booked Room has been created successfully.',
            'data' => $bookedRoom
        ], Response::HTTP_CREATED);
    }

    public function show(BookedRoom $bookedRoom)
    {
        return response([
            'success' => true,
            'message' => 'Data of an individual Booked Room',
            'data' => $bookedRoom
        ], Response::HTTP_CREATED);
    }

    public function update(BookedRoom $bookedRoom)
    {
        $bookedRoom->update($this->validateRequest());
        return response([
            'success' => true,
            'message' => 'Booked Room has been updated',
            'data' => $bookedRoom
        ], Response::HTTP_CREATED);
    }

    public function destroy(BookedRoom $bookedRoom)
    {
        $bookedRoom->delete();
        return response([
            'success' => true,
            'message' => 'Booked Room has been deleted successfully.'
        ], Response::HTTP_NO_CONTENT);
    }

    public function validateRequest()
    {
        return request()->validate([
            'booking_id' => 'required',
            'room_category_id' => 'required',
            'number_of_rooms' => 'required'
        ]);

    }
}
