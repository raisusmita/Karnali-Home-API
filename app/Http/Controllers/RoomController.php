<?php

namespace App\Http\Controllers;

use App\Model\Room;

class RoomController extends Controller
{
    public function index()
    {
        $room = Room::all();
        if ($room->isNotEmpty()) {
            return $this->jsonResponse(true, 'List of Rooms', $room);
        } else {
            return $this->jsonResponse(false, 'There is no any room yet');
        }
    }

    public function store()
    {
        $room = Room::create($this->validateRequest());
        return $this->jsonResponse(true, 'Room has been created successfully', $room);
    }

    public function show(Room $room)
    {
        return $this->jsonResponse(true, 'Data of an individual Room', $room);
    }

    public function update(Room $room)
    {
        $room->update($this->validateRequest());
        return $this->jsonResponse(true, 'Room has been updated.', $room);
    }

    public function destroy(Room $room)
    {
        $room->delete();
        return $this->jsonResponse(true, 'Room has been deleted successfully.');
    }

    private function validateRequest()
    {
        return request()->validate([
            'room_category_id' => 'required',
            'room_number' => 'required |unique:rooms',
            'number_of_bed' => 'required',
            'telephone_number' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/',
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
