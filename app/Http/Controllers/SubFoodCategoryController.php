<?php

namespace App\Http\Controllers;

use App\Model\FoodItems;
use App\Model\SubFoodCategory;
use Illuminate\Http\Request;

class SubFoodCategoryController extends Controller
{
    public function index()
    {
        $subFood = SubFoodCategory::all();
        if ($subFood->isNotEmpty()) {
            return $this->jsonResponse(true, 'Lists of sub foods.', $subFood);
        } else {
            return $this->jsonResponse(false, 'Currently, there is no any sub food yet.', $subFood);
        }
    }

    public function store()
    {
        $subFood = SubFoodCategory::create($this->validateRequest());
        return $this->jsonResponse(true, 'Sub Food Category has been created successfully.', $subFood);
    }

    public function update(SubFoodCategory $subFood)
    {
        $subFood->update($this->validateRequest());
        return $this->jsonResponse(true, 'Sub food category has been updated.', $subFood);
    }

    public function getSubAndFoodItemsById()
    {
        $foodList = array(
            "subFood" => [],
            "foodItems" => []
        );
        $subFood = SubFoodCategory::where('main_food_category_id', request()->id)->get();
        foreach ($subFood as $food) {
            $food->foodItems;
        }
        $foodItems = FoodItems::where('main_food_category_id', request()->id)->where('sub_food_category_id', null)->get();
        $foodList["subFood"] = $subFood;
        $foodList["foodItems"] = $foodItems;
        return $this->jsonResponse(true, 'Lists of sub foods.', $foodList);
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
