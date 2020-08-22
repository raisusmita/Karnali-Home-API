<?php

namespace App\Http\Controllers;

use App\Model\FoodItems;
use App\Model\MainFoodCategory;
use App\Model\SubFoodCategory;
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
            if ($food->food_header_id != null) {
                $food->foodHeader;
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

    public function getMainFoodCategory()
    {
        $mainFood = MainFoodCategory::all();
        if ($mainFood->isNotEmpty()) {
            return $this->jsonResponse(true, 'Lists of main foods.', $mainFood);
        } else {
            return $this->jsonResponse(false, 'Currently, there is no any main food yet.', $mainFood);
        }
    }

    public function getSubFoodCategory()
    {
        $subFood = SubFoodCategory::all();
        if ($subFood->isNotEmpty()) {
            return $this->jsonResponse(true, 'Lists of sub foods.', $subFood);
        } else {
            return $this->jsonResponse(false, 'Currently, there is no any sub food yet.', $subFood);
        }
    }

    private function validateRequest()
    {
        return request()->validate([
            'main_food_category_id' => 'required',
            'sub_food_category_id' => 'sometimes',
            'food_header_id' => 'sometimes',
            'food_name' => 'required',
            'price' => 'required',
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
