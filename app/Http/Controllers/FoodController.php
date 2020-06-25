<?php

namespace App\Http\Controllers;

use App\Model\Food;

class FoodController extends Controller
{
    public function index()
    {
        $food = Food::all();
        if ($food->isNotEmpty()) {
            return response()->json([
                'success' => true,
                'message' => 'Lists of Foods.',
                'data' => $food,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Currently, there is no Food.',
            ]);
        }
    }

    public function store()
    {
        $food = Food::create($this->validateRequest());
        return response()->json([
            'success' => true,
            'message' => 'Food has been created successfully.',
            'data' => $food,
        ]);
    }

    public function show(Food $food)
    {
        return response()->json([
            'success' => true,
            'message' => 'Data of an individual Food',
            'data' => $food,
        ]);
    }

    public function update(Food $food)
    {
        $food->update($this->validateRequest());
        return response()->json([
            'success' => true,
            'message' => 'Food has been updated',
            'data' => $food,
        ]);
    }

    public function destroy(Food $food)
    {
        $food->delete();
        return response()->json([
            'success' => true,
            'message' => 'Food has been deleted successfully.',
        ]);
    }

    private function validateRequest()
    {
        return request()->validate([
            'name' => 'required',
            'price' => 'required',
            'food_type' => 'required',
        ]);
    }
}
