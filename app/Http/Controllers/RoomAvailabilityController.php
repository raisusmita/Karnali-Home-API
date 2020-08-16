<?php

namespace App\Http\Controllers;

use App\Model\Room;
use App\Model\RoomAvailability;
use App\Model\Reservation;
use Illuminate\Http\Request;

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
        // $room = $room->groupBy('room_category_id');

        return $this->jsonResponse(true, 'Available Rooms.', $room);
    }

    public function getUnavailableRoom()
    {
        // getting unavailable data 
        $unAvailableRoom = RoomAvailability::unavailable()->get();
        $roomIds = [];
        foreach ($unAvailableRoom as $av) {
            array_push($roomIds, $av->room_id);
        }
        $room = Room::whereIn('id', $roomIds)->get();
        $room->map(function ($roomCat) {
            $roomCat->roomCategory->id;
        });
        return $this->jsonResponse(true, 'UnAvailable Rooms.', $room);
    }

    public function getAvailableRoomByDate()
    {
        $dateValue = request();
        $format = "Y-m-d H:i:s";
        if ($dateValue->check_in_date < $dateValue->check_out_date) {
            $unAvailableRoom = RoomAvailability::whereBetween('check_in_date', [date($format, strtotime($dateValue->check_in_date)), date($format, strtotime($dateValue->check_out_date))])
                ->orWhereBetween('check_out_date', [date($format, strtotime($dateValue->check_in_date)), date($format, strtotime($dateValue->check_out_date))])
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
            return $this->jsonResponse(true, 'Available Rooms by date.', $availableRoom);
        } else {
            return $this->jsonResponse(true, 'invalid Date.');
        }
    }

    public function storeRoomAvailability(Request $request)
    {
        $roomId = request();
        $test = RoomAvailability::where('room_id', $roomId->room_id)->get();
        if (is_null($test)) {
            return $this->jsonResponse(true, 'The room is already taken.');
        } else {
            $roomAvailability = RoomAvailability::insert($request->all());
            return $this->jsonResponse(true, 'Stored in room availability', $roomAvailability);
        }
    }

    public function getRoomByBookingId()
    {
        $bookingId = request();
        $bookedRoom = RoomAvailability::where('booking_id', $bookingId->bookingId)->get();
        $bookedRoom->map(function ($bookedRoom) {
            // $bookedRoom->Room;
            $bookedRoom->Room->roomCategory->id;
        });
        return $this->jsonResponse(true, 'Individual Room.', $bookedRoom);
    }

    public function updateBookingToReservation()
    {
        $reservation = request();
        $selected = RoomAvailability::where('booking_id', '=', $reservation->booking_id)
            ->where('room_id', '=', $reservation->room_id)
            ->update([
                'reservation_id' => $reservation->reservation_id,
                'check_in_date' => $reservation->check_in_date,
                'check_out_date' => $reservation->check_out_date
            ]);
        return $this->jsonResponse(true, 'Room Availability has been updated.');
    }

    public function getRoomByCustomerId()
    {
        $customerId = request();
        $totalRoomDetails =array();
        // Get reservation id for the selected customer
        $reservations = Reservation::where(['customer_id' => $customerId->customer_id])->get();

        if($reservations->isNotEmpty()){
            foreach($reservations as $reservation){
                // Getting roomAvailability details for each reservation with availability 1 if the reservations has made.
                $roomAvailablilityDetail = RoomAvailability::where([
                    'reservation_id' => $reservation['id'], 
                    'availability'=>'1'
                    ])->get();

                    // Get room and roomCategory details
                    $room = Room::where(['id'=>$roomAvailablilityDetail[0]['room_id']])->get();
                    $room->map(function ($roomCat) {
                        $roomCat->roomCategory->id;
                    });
                    
                    // attached room/roomCategory details to roomAvailability
                    $roomAvailablilityDetail[0]['room_id'] = $room;
                    
                // trim is used to remove [] from each object 
                // json_decode is used to convert the string to array
                array_push($totalRoomDetails,json_decode(trim($roomAvailablilityDetail, '[]')));
            }
            
            return $this->jsonResponse(true, 'List of rooms for transaction.', $totalRoomDetails);
        }else{
            return $this->jsonResponse(false, 'No reservation has made for this customer');
        }
    }

    private function validateRequest()
    {
        return request()->validate([
            'room_id' => 'required |unique:room_availabilities',
            'reservation_id' => 'nullable',
            'booking_id' => 'required',
            'check_in_date' => 'required',
            'check_out_date' => 'required',
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
