<?php

namespace App\Http\Controllers;

use App\Model\Room;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


class RoomController extends Controller
{
    public function index()
    {
        $room = Room::all();
        if ($room->isNotEmpty()) {
            return response([
                'success' => true,
                'message' => 'Lists of Customers.',
                'data' => $room
            ], Response::HTTP_CREATED);
        } else {
            return response([
                'success' => false,
                'message' => 'Currently, there is no any Customers yet.',
            ], Response::HTTP_CREATED);
        }
    }

    public function store(Request $request)
    {
        
        if($request->hasFile('image')){
            //Get Filename with the extension
            $fileNameWithExt = $request->file('image')->getClientOriginalName();

            //Get just filename
            $filename = pathinfo($fileNameWithExt, PATHINFO_FILENAME);

            //Get just extension
            $extension = $request->file('image')->getClientOriginalExtension();

            //Filenameto store
            $fileNameToStore = $filename.'_'.time().'.'.$extension;

            //Upload Image
            $path = $request->file('image')->storeAs('public/images', $fileNameToStore);
        }else{
            $fileNameToStore = 'noimage.jpg';
        }

        $room = new Room;
        $room->image = $fileNameToStore;
        $room->room_category_id = $request->room_category_id;
        $room->room_number = $request->room_number;
        $room->number_of_bed = $request->number_of_bed;
        $room->phone_number = $request->phone_number;
        $room->save();

        // $room = Room::create($this->validateRequest());
        
        
        
        return response([
            'success' => true,
            'message' => 'Room has been created successfully.',
            'data' => $room
        ], Response::HTTP_CREATED);
    }

    public function show(Room $room, Request $request)
    {


        return response([
            'success' => true,
            'message' => 'Data of an individual Room',
            'data' => $room
        ], Response::HTTP_CREATED);
    }

    public function update(Room $room)
    {
        $room->update($this->validateRequest());
        return response([
            'success' => true,
            'message' => 'Room has been updated',
            'data' => $room
        ], Response::HTTP_CREATED);
    }

    public function destroy(Room $room)
    {
        $room->delete();
        return response([
            'success' => true,
            'message' => 'Room has been deleted successfully.'
        ], Response::HTTP_NO_CONTENT);
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
