<?php 

namespace App\Services;
use App\Model\Room;
use App\Model\RoomAvailability;

class RoomAvailabilityServices

{
    public function getAvailableRoom()
    {

        $unAvailableRoom = RoomAvailability::available()->get();
        $roomIds = [];
        foreach ($unAvailableRoom as $av) {
            array_push($roomIds, $av->room_id);
        } 
        $room = Room::whereNotIn('id', $roomIds)->get();
        $room->map(function ($roomCat) {
            $roomCat->roomCategory->id;
        });

        return $this->jsonResponse(true, 'Available Rooms.', $room);
    }

    public function storeRoomAvailability($rooms)
    {

        if (is_null($rooms)) {
            return $this->jsonResponse(true, 'The room is already taken.');
        } else {
            $roomAvailability = RoomAvailability::insert($rooms);
            return $this->jsonResponse(true, 'Stored in room availability', $roomAvailability);
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
}