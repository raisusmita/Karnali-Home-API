<?php

namespace App\Http\Controllers;

use App\Model\RoomCategory;
use Intervention\Image\Facades\Image;

class RoomCategoryController extends Controller
{
    public function index()
    {
        $roomCategory = RoomCategory::all();
        if ($roomCategory->isNotEmpty()) {
            $roomCategory->map(function ($roomCategory) {
                $roomCategory->image = $roomCategory->image ? asset('storage/' . $roomCategory->image) : "";
            });
            return response()->json([
                'success' => true,
                'message' => 'Lists of Room Category.',
                'data' => $roomCategory,
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
        $this->storeImage($roomCategory);
        return response()->json([
            'success' => true,
            'message' => 'Room Category has been created successfully.',
            'data' => $roomCategory,
        ]);
    }

    public function show(RoomCategory $roomCategory)
    {
        $roomCategory->image = $roomCategory->image ? asset('storage/' . $roomCategory->image) : "";
        return response()->json([
            'success' => true,
            'message' => 'Data of an individual Room Category',
            'data' => $roomCategory,
        ]);
    }

    public function update(RoomCategory $roomCategory)
    {
        $roomCategory->update($this->validateRequest());
        return response()->json([
            'success' => true,
            'message' => 'Room Category has been updated',
            'data' => $roomCategory,
        ]);
    }

    public function destroy(RoomCategory $roomCategory)
    {
        $roomCategory->delete();
        return response()->json([
            'success' => true,
            'message' => 'Room Category has been deleted successfully.',
        ]);
    }

    private function storeImage($roomCategory)
    {
        if (request()->has('image')) {
            $roomCategory->update([
                'image' => request()->image->store('images', 'public'),
            ]);

            $img = Image::make(public_path('storage/' . $roomCategory->image))->fit(386, 235);
            $img->save();
        }
    }

    private function validateRequest()
    {
        return request()->validate([
            'room_category' => 'required',
            'room_type' => 'required',
            'number_of_rooms' => 'required',
            'room_price' => 'required',
            'image' => 'image|nullable|sometimes',
        ]);
    }
}
