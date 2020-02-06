<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Room;
use Symfony\Component\HttpFoundation\Response;


class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $room = Room::all();
        return $room;
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $room = Room::create($this->validateRequest());
        return response([
            'data' => $room
        ], Response::HTTP_CREATED);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $room
     * @return \Illuminate\Http\Response
     */

    public function show(Room $room)
    {
        return $room;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Room $room)
    {
        $room->update($this->validateRequest());
        return response([
            'data' => $room
        ], Response::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Room $room)
    {
        $room->delete();
        return response(null, Response::HTTP_NO_CONTENT);
    }

    
    private function validateRequest()
    {
        return request()->validate([
            'room_category_id'=>'required',
            'room_number'=>'required |unique:rooms',
            'number_of_bed'=>'required',
            'phone_number'=>'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10'
        ]);
    }
}
