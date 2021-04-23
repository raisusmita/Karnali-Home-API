<?php

namespace App\Http\Controllers;

use App\Model\Room;
use App\Model\RoomCategory;
use Illuminate\Http\Request;


class RoomController extends Controller
{
    public function index()
    {
        $room = Room::all();
        if ($room->isNotEmpty()) {
            $room->map(function ($room) {
                $room->roomCategory;
            });
            return $this->jsonResponse(true, 'List of Rooms', $room);
        } else {
            return $this->jsonResponse(false, 'There is no any room yet');
        }
    }

    public function getRoomList(Request $request){

        $skip =$request->skip;
        $limit=$request->limit;
        $totalRoom = Room::get()->count();

        // using where clause just to get data in required format
        $room = Room::where('id','!=', 0)->skip($skip)->take($limit)->orderBy('id', 'DESC')->get();
        if ($room->isNotEmpty()) {
            $room->map(function ($room) {
                $room->roomCategory;
            });
            return $this->jsonResponse(true, 'List of Rooms', $room, $totalRoom);
        } else {
            return $this->jsonResponse(false, 'There is no any room yet');
        }
    }

    public function getRoomBasedOnCategory()
    {
        $category = request();
        $room = Room::where('room_category_id','=', $category->room_category_id)->get();
        if ($room->isNotEmpty()) {
            return $this->jsonResponse(true, 'List of rooms based on category', $room);
        } else {
            return $this->jsonResponse(false, 'There is no any room yet');
        }
    }


    public function store()
    {
        $room = Room::create($this->validateAddRequest());
        return $this->jsonResponse(true, 'Room has been created successfully', $room);
    }

    public function show(Room $room)
    {
        return $this->jsonResponse(true, 'Data of an individual Room', $room);
    }

    public function update(Room $room)
    {
        $room->update($this->validateEditRequest());
        return $this->jsonResponse(true, 'Room has been updated.', $room);
    }

    public function destroy(Room $room)
    {
        $room->delete();
        return $this->jsonResponse(true, 'Room has been deleted successfully.');
    }

    private function validateAddRequest()
    {
        return request()->validate([
            'room_category_id' => 'required',
            'room_number' => 'required |unique:rooms',
            'number_of_bed' => 'required',
            'telephone_number' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/',
        ]);
    }

    private function validateEditRequest()
    {
        return request()->validate([
            'room_category_id' => 'required',
            'room_number' => 'required |unique:rooms,room_number,'. request()->id,
            'number_of_bed' => 'required',
            'telephone_number' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/',
        ]);
    }


    private function jsonResponse($success = false, $message = '', $data = null, $totalRoom=0)
    {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data' => $data,
            'totalCount'=>$totalRoom
        ]);
    }
}
