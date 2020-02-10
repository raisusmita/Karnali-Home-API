<?php

namespace App\Http\Controllers;

use App\Model\RoomCategory;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoomCategoryController extends Controller
{
    public function index()
    {
        $roomCategory = RoomCategory::all();
        if ($roomCategory->isNotEmpty()) {
            return response([
                'success' => true,
                'message' => 'Lists of Room Category.',
                'data' => $roomCategory
            ], Response::HTTP_CREATED);
        } else {
            return response([
                'success' => false,
                'message' => 'Currently, there is no any Room Category.',
            ], Response::HTTP_CREATED);
        }
    }

    public function store()
    {
        $roomCategory = RoomCategory::create($this->validateRequest());
        return response([
            'success' => true,
            'message' => 'Room Category has been created successfully.',
            'data' => $roomCategory
        ], Response::HTTP_CREATED);
    }

    public function show(RoomCategory $roomCategory)
    {
        return response([
            'success' => true,
            'message' => 'Data of an individual Room Category',
            'data' => $roomCategory
        ], Response::HTTP_CREATED);
    }

    public function update(RoomCategory $roomCategory)
    {
        $roomCategory->update($this->validateRequest());
        return response([
            'success' => true,
            'message' => 'Room Category has been updated',
            'data' => $roomCategory
        ], Response::HTTP_CREATED);
    }

    public function destroy(RoomCategory $roomCategory)
    {
        $roomCategory->delete();
        return response([
            'success' => true,
            'message' => 'Room Category has been deleted successfully.'
        ], Response::HTTP_NO_CONTENT);
    }

    private function validateRequest()
    {
        return request()->validate([
            'room_category' => 'required |unique:room_categories',
            'number_of_room' => 'required',
            'room_price' => 'required'
        ]);
    }
}
