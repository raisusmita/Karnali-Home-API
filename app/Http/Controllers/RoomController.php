<?php

namespace App\Http\Controllers;

use App\Model\Room;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


class RoomController extends Controller
{
    public function index()
    {
        $room = Room::all();
        if ($room->isNotEmpty()) {
            return response([
                'success' => true,
                'message' => 'Lists of Customers.',
                'data' => $room
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
        $room = Room::create($this->validateRequest());
        return response([
            'success' => true,
            'message' => 'Room has been created successfully.',
            'data' => $room
        ], Response::HTTP_CREATED);
    }

    public function show(Room $room)
    {
        return response([
            'success' => true,
            'message' => 'Data of an individual Room',
            'data' => $room
        ], Response::HTTP_CREATED);
    }

    public function update(Room $room)
    {
        $room->update($this->validateRequest());
        return response([
            'success' => true,
            'message' => 'Room has been updated',
            'data' => $room
        ], Response::HTTP_CREATED);
    }

    public function destroy(Room $room)
    {
        $room->delete();
        return response([
            'success' => true,
            'message' => 'Room has been deleted successfully.'
        ], Response::HTTP_NO_CONTENT);
    }

    private function validateRequest()
    {
        return request()->validate([
            'room_category_id' => 'required',
            'room_number' => 'required |unique:rooms',
            'number_of_bed' => 'required',
            'phone_number' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10'
        ]);
    }
}
