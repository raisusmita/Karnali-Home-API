<?php

namespace App\Http\Controllers;

use App\Model\FoodHeader;
use Illuminate\Http\Request;

class FoodHeaderController extends Controller
{
    public function index()
    {
        $foodHeader = FoodHeader::all();
        if ($foodHeader->isNotEmpty()) {
            return $this->jsonResponse(true, 'Lists of foods headers.', $foodHeader);
        } else {
            return $this->jsonResponse(false, 'Currently, there is no any food headers yet.', $foodHeader);
        }
    }

    public function store()
    {
        $foodHeader = FoodHeader::create($this->validateRequest());
        return $this->jsonResponse(true, 'Food Header Category has been created successfully.', $foodHeader);
    }

    public function update(FoodHeader $foodHeader)
    {
        $foodHeader->update($this->validateRequest());
        return $this->jsonResponse(true, 'food Header category has been updated.', $foodHeader);
    }

    private function validateRequest()
    {
        return request()->validate([
            'main_food_category_id' => 'required',
            'sub_food_name' => 'required',
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
