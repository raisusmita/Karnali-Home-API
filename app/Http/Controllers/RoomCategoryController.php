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
            return $this->jsonResponse(true, 'Lists of Room Category.', $roomCategory);
        } else {
            return $this->jsonResponse(false, 'Currently, there is no any Room Category.', $roomCategory);
        }
    }

    public function store()
    {
        $roomCategory = RoomCategory::create($this->validateRequest());
        $this->storeImage($roomCategory);
        return $this->jsonResponse(true, 'Room Category has been created successfully.', $roomCategory);
    }

    public function show(RoomCategory $roomCategory)
    {
        $roomCategory->image = $roomCategory->image ? asset('storage/' . $roomCategory->image) : "";
        return $this->jsonResponse(true, 'Data of an individual Room Category.', $roomCategory);
    }

    public function update(RoomCategory $roomCategory)
    {
        $roomCategory->update($this->validateRequest());
        // $this->storeImage($roomCategory);
        return $this->jsonResponse(true, 'Room Category has been updated.', $roomCategory);
    }

    public function editRoomCategory()
    {
        $roomCategory = RoomCategory::find(request()->id);
        $roomCategory->update($this->validateRequest());
        $this->storeImage($roomCategory);
        return $this->jsonResponse(true, 'Room Category has been updated.', $roomCategory);
    }

    public function destroy(RoomCategory $roomCategory)
    {
        $roomCategory->delete();
        return $this->jsonResponse(true, 'Room Category has been deleted successfully.');
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
            'image' => 'file|image|mimes:jpeg,png,jpg,gif|nullable|sometimes',
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
