<?php

namespace App\Http\Controllers;

use App\Model\RoomCategory;

class RoomCategoryController extends Controller
{
    public function index()
    {
        $roomCategory = RoomCategory::all();
        if ($roomCategory->isNotEmpty()) {
            return response()->json([
                'success' => true,
                'message' => 'Lists of Room Category.',
                'data' => $roomCategory
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Currently, there is no any Room Category.',
            ]);
        }
    }

    public function store()
    {
        $roomCategory = RoomCategory::create($this->validateRequest());
        return response()->json([
            'success' => true,
            'message' => 'Room Category has been created successfully.',
            'data' => $roomCategory
        ]);
    }

    public function show(RoomCategory $roomCategory)
    {
        return response()->json([
            'success' => true,
            'message' => 'Data of an individual Room Category',
            'data' => $roomCategory
        ]);
    }

    public function update(RoomCategory $roomCategory)
    {
        $roomCategory->update($this->validateRequest());
        return response()->json([
            'success' => true,
            'message' => 'Room Category has been updated',
            'data' => $roomCategory
        ]);
    }

    public function destroy(RoomCategory $roomCategory)
    {
        $roomCategory->delete();
        return response()->json([
            'success' => true,
            'message' => 'Room Category has been deleted successfully.'
        ]);
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
