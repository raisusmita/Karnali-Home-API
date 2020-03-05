<?php

namespace App\Http\Controllers;

use App\Model\Room;
use App\Model\RoomAvailability;

class RoomAvailabilityController extends Controller
{

    public function getAvailableRoom()
    {
        // getting unavailable data to filter available
        
        $unAvailableRoom = RoomAvailability::available()->get();
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

    // public function getAvailableRoomByDate()
    // {
    //     $dateValue =  request();
    //     if ($dateValue->check_in_date < $dateValue->check_out_date) {
    //         // $unAvailableRoom = RoomAvailability::unavailable()
    //         //     ->where('check_out_date', '>=', $dateValue->check_in_date or 'check_in_date', '>', $dateValue->check_out_date)
    //         //     ->get();
    //         $unAvailableRoom = RoomAvailability::unavailable()
    //             // ->where('check_out_date', '>=', $dateValue->check_in_date and 'check_out_date', '>', $dateValue->check_out_date)
    //             // ->where('check_in_date', '<', $dateValue->check_out_date and 'check_out_date', '<=', $dateValue->check_out_date)
    //             ->whereBetween('check_in_date', [$dateValue->check_in_date, $dateValue->check_out_date])
    //             ->get();
    //         $roomIds = [];
    //         foreach ($unAvailableRoom as $av) {
    //             array_push($roomIds, $av->room_id);
    //         }
    //         $room = Room::whereNotIn('id', $roomIds)->get();
    //         $room->map(function ($roomCat) {
    //             $roomCat->roomCategory->id;
    //         });
    //         $room = $room->groupBy('room_category_id');

    //         return $room;
    //     } else {
    //         return 'Invalid Date';
    //     }
    // }


    public function getAvailableRoomByDate()
    {
        $dateValue =  request();

        if ($dateValue->check_in_date < $dateValue->check_out_date) {
            $unAvailableRoom = RoomAvailability::whereDate(
                'check_in_date',
                '>=',
                $dateValue->check_in_date,
                'and',
                'check_in_date',
                '<=',
                $dateValue->check_out_date
            )
                ->orWhereDate('check_out_date', '<=', $dateValue->check_out_date, 'and', 'check_out_date', '<=', $dateValue->check_out_date)
                // ->orWhere(('check_out_date' >= $dateValue->check_in_date and 'check_out_date' <= $dateValue->check_in_date))
                // ->orWhereBetween('check_out_date', [$dateValue->check_in_date, $dateValue->check_out_date])
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

    public function storeRoomAvailability()
    {
        $roomId = request();
        $test = RoomAvailability::where('room_id', $roomId->room_id)->get();
        if( is_null($test))
        {

            return response()->json([
                'success' => false,
                'message' => 'The room is already taken'
            ]);
        }
        else{
            $roomAvailability = RoomAvailability::create($this->validateRequest());
            return response()->json([
                'success' => true,
                'message' => 'Room Availability has been created successfully.',
                'data' => $roomAvailability
            ]);
        }
        

    }

    private function validateRequest()
    {
        return request()->validate([
            'room_id' => 'required |unique:room_availabilities',
            'reservation_id' => 'nullable',
            'booking_id' => 'required',
            'check_in_date' => 'required',
            'check_out_date' => 'required'
        ]);
    }
}
