<?php

namespace App\Http\Controllers;

use App\Model\FoodItems;
use App\Model\MainFoodCategory;
use Illuminate\Http\Request;

class FoodItemsController extends Controller
{
    public function index()
    {
        $food = FoodItems::all();
        $food->map(function ($food) {
            if ($food->sub_food_category_id != null) {
                $food->subFoodCategory;
                $food->subFoodCategory->mainFoodCategory;
            }
            if ($food->main_food_category_id != null) {
                $food->mainFoodCategory;
            }
        });
        if ($food->isNotEmpty()) {
            return $this->jsonResponse(true, 'Lists of foods.', $food);
        } else {
            return $this->jsonResponse(false, 'Currently, there is no any food yet.', $food);
        }
    }

    public function store()
    {
        $food = FoodItems::create($this->validateRequest());
        return $this->jsonResponse(true, 'FoodItems has been created successfully.', $food);
    }

    public function show(FoodItems $food)
    {
        return $this->jsonResponse(true, 'Data of an individual FoodItems.', $food);
    }

    public function update(FoodItems $food)
    {
        $food->update($this->validateRequest());
        return $this->jsonResponse(true, 'FoodItems has been updated.', $food);
    }

    public function destroy(FoodItems $food)
    {
        $food->delete();
        return $this->jsonResponse(true, 'FoodItems has been deleted successfully.');
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
