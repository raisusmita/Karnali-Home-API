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

        if ($dateValue->check_in_date < $dateValue->check_out_date) {
            $unAvailableRoom = RoomAvailability::whereDate('check_in_date', '>=', $dateValue->check_in_date, 'or', 'check_in_date', '<=', $dateValue->check_out_date)
                // ->orWhere('check_out_date', '<=', $dateValue->check_out_date, 'and', 'check_out_date', '<=', $dateValue->check_out_date)
                // ->orWhere(('check_out_date' >= $dateValue->check_in_date and 'check_out_date' <= $dateValue->check_in_date))
                ->orWhereDate('check_out_date', '>=', $dateValue->check_in_date, 'or', 'check_out_date', '<=', $dateValue->check_out_date)
                ->unavailable()
                ->get();
            $roomIds = [];
            foreach ($unAvailableRoom as $av) {
                array_push($roomIds, $av->room_id);
            }
            $availableRoom = Room::whereNotIn('id', $roomIds)->get();
            $availableRoom->map(function ($roomCat) {
                $roomCat->roomCategory->id;
            });
            $availableRoom = $availableRoom->groupBy('room_category_id');

            return $availableRoom;
        } else {
            return 'Invalid Date';
        }
    }
}
