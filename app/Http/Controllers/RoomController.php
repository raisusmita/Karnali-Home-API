<?php

namespace App\Http\Controllers;

use App\Model\Room;

class RoomController extends Controller
{
    public function index()
    {
        $room = Room::all();
        if ($room->isNotEmpty()) {
            $room->map(function ($room) {
                $room->image = $room->image ? public_path('storage/' . $room->image) : "No image";
            });
            return response()->json([
                'success' => true,
                'message' => 'Lists of Room.',
                'data' => $room,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Currently, there is no any Room yet.',
            ]);
        }
    }

    public function store()
    {
        $room = Room::create($this->validateRequest());
        $this->storeImage($room);
        return response()->json([
            'success' => true,
            'message' => 'Room has been created successfully.',
            'data' => $room
        ]);
    }

    public function show(Room $room)
    {
        $room->image = $room->image ? public_path('storage/' . $room->image) : "No image";
        return response()->json([
            'success' => true,
            'message' => 'Data of an individual Room',
            'data' => $room,
        ]);
    }

    public function update(Room $room)
    {
        $room->update($this->validateRequest());
        $this->storeImage($room);
        return response()->json([
            'success' => true,
            'message' => 'Room has been updated',
            'data' => $room,
        ]);
    }

    public function destroy(Room $room)
    {
        $room->delete();
        return response()->json([
            'success' => true,
            'message' => 'Room has been deleted successfully.'
        ]);
    }

    private function storeImage($room)
    {
        if (request()->has('image')) {
            $room->update([
                'image' => request()->image->store('images', 'public'),
            ]);
        }
    }

    private function validateRequest()
    {
        return request()->validate([
            'room_category_id' => 'required',
            'room_number' => 'required |unique:rooms',
            'number_of_bed' => 'required',
            'phone_number' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'image' => 'image|nullable|max:1999'
        ]);
    }
}
