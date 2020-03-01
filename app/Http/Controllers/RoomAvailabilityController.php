<?php

namespace App\Http\Controllers;

use App\Model\Room;
use App\RoomAvailability;

class RoomAvailabilityController extends Controller
{

    public function getAvailableRoom()
    {
        // getting unavailable data to filter available
        $unAvailableRoom = RoomAvailability::unavailable()->get();
        $roomIds = [];
        foreach ($unAvailableRoom as $av) {
            array_push($roomIds, $av->room_id);
        }
        $room = Room::whereNotIn('id', $roomIds)->get();
        $room->map(function ($roomCat) {
            $roomCat->roomCategory->id;
        });
        $room = $room->groupBy('room_category_id');

        return $room;
    }

    public function getAvailableRoomByDate()
    {
        $dateValue =  request();
        $unAvailableRoom = RoomAvailability::unavailable()
            ->where('check_out_date', '>=', $dateValue->check_in_date)
            ->get();
        $roomIds = [];
        foreach ($unAvailableRoom as $av) {
            array_push($roomIds, $av->room_id);
        }
        $room = Room::whereNotIn('id', $roomIds)->get();
        $room->map(function ($roomCat) {
            $roomCat->roomCategory->id;
        });
        $room = $room->groupBy('room_category_id');

        return $room;
    }
}
