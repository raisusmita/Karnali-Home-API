<?php

namespace App\Http\Controllers;

use App\Model\Room;
use App\Model\RoomAvailability;
use Illuminate\Http\Request;

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
        // $room = $room->groupBy('room_category_id');

        return $room;
    }

    public function getAvailableRoomByDate()
    {
        $dateValue =  request();

        if ($dateValue->check_in_date < $dateValue->check_out_date) {
            $unAvailableRoom = RoomAvailability::whereDate('check_in_date', '>=', $dateValue->check_in_date, 'and', 'check_in_date', '<=', $dateValue->check_out_date)
                ->orWhereDate('check_out_date', '>=', $dateValue->check_in_date, 'and', 'check_out_date', '<=', $dateValue->check_out_date)
                ->available()
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

    public function storeRoomAvailability(Request $request)
    {
        $roomId = request();
        $test = RoomAvailability::where('room_id', $roomId->room_id)->get();
        if (is_null($test)) {

            return response()->json([
                'success' => false,
                'message' => 'The room is already taken'
            ]);
        } else {
            $roomAvailability = RoomAvailability::insert($request->all());
            return response()->json([
                'success' => true,
                'message' => 'Room Availability has been created successfully.',
                'data' => $roomAvailability
            ]);
        }
    }

    public function getRoomByBookingId()
    {
        $bookingId = request();
        // return $bookingId->bookingId;
        $bookedRoom =  RoomAvailability::where('booking_id', $bookingId->bookingId)->get();
        $bookedRoom->map(function ($bookedRoom) {
            $bookedRoom->Room;
        });
        return response()->json([
            'success' => true,
            'message' => 'Customer has been updated',
            'data' => $bookedRoom
        ]);
        
    }

    public function updateBookingToReservation(){
        $reservation =request();
        $selected = RoomAvailability::where('booking_id','=', $reservation->booking_id)
                                    ->where( 'room_id','=', $reservation->room_id)
                                    ->update([  'reservation_id'=> $reservation->reservation_id, 
                                                'check_in_date'=>$reservation->check_in_date, 
                                                'check_out_date'=>$reservation->check_out_date
                                            ]);
        return response()->json([
            'success' => true,
            'message' => 'Room Availability has been updated',
            // 'data' => $bookedRoom
           ]);
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
