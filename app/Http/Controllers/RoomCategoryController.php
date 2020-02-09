<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\RoomCategory;
use Symfony\Component\HttpFoundation\Response;

class RoomCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roomCategory = RoomCategory::all();
        return $roomCategory;
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {

        $roomCategory = RoomCategory::create($this->validateRequest());
        return response([
            'data' => $roomCategory
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(RoomCategory $roomCategory)
    {
        //
        return $roomCategory;

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RoomCategory $roomCategory)
    {
        //
        $roomCategory->update($this->validateRequest());
        return response([
            'data' => $roomCategory
        ], Response::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy( RoomCategory $roomCategory)
    {
        //
        $roomCategory->delete();
        return response(null, Response::HTTP_NO_CONTENT);
    }

    private function validateRequest()
    {
        return request()->validate([
            'room_category'=>'required |unique:room_categories',
            'number_of_room'=>'required',
            'room_price'=>'required'
        ]);
    }
}
