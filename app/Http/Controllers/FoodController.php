<?php

namespace App\Http\Controllers;

use App\Model\Food;

class FoodController extends Controller
{
    public function index()
    {
        $food = Food::all();
        if ($food->isNotEmpty()) {
            return $this->jsonResponse(true, 'Lists of foods.', $food);
        } else {
            return $this->jsonResponse(false, 'Currently, there is no any foods yet.', $food);
        }
    }

    public function store()
    {
        $food = Food::create($this->validateRequest());
        return $this->jsonResponse(true, 'Food has been created successfully.', $food);
    }

    public function show(Food $food)
    {
        return $this->jsonResponse(true, 'Data of an individual Food.', $food);
    }

    public function update(Food $food)
    {
        $food->update($this->validateRequest());
        return $this->jsonResponse(true, 'Food has been updated.', $food);
    }

    public function destroy(Food $food)
    {
        $food->delete();
        return $this->jsonResponse(true, 'Food has been deleted successfully.');
    }

    private function validateRequest()
    {
        return request()->validate([
            'name' => 'required',
            'price' => 'required',
            'food_type' => 'required',
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
