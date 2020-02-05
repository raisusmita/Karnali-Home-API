<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\RoomCategory;
use App\Http\Resources\RoomCategory\RoomCategoryResource;
use App\Http\Resources\RoomCategory\RoomCategoryCollection;
use App\Http\Requests\RoomCategoryRequest;
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
        return RoomCategoryCollection::collection(RoomCategory::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoomCategoryRequest $request)
    {
        //
        
        $roomCategory = new RoomCategory;
        $roomCategory->room_category = $request->room_category;
        $roomCategory->number_of_room = $request->no_of_rooms;
        $roomCategory->save();
        return response([
            'data' => new RoomCategoryResource($roomCategory)
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $roomCategory = RoomCategory::find($id);
        return new RoomCategoryResource($roomCategory);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RoomCategory $roomCategory)
    {
        //
        $request['number_of_room'] = $request->no_of_rooms;
        unset($request['no_of_rooms']);

        $roomCategory->update($request->all());
        return response([
            'data' => new RoomCategoryResource($roomCategory)
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
}
