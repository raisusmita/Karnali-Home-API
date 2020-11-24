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

    public function getSubFoodList(Request $request){
        $skip =$request->skip;
        $limit=$request->limit;
        $totalSubFood = SubFoodCategory::get()->count();

        $subFood = SubFoodCategory::skip($skip)->take($limit)->get();
        if ($subFood->isNotEmpty()) {
            return $this->jsonResponse(true, 'Lists of sub foods.', $subFood, $totalSubFood);
        } else {
            return $this->jsonResponse(false, 'Currently, there is no any sub food yet.', $subFood, $totalSubFood);
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
            $foodItemsData =  FoodItems::where('main_food_category_id', request()->id)->where('sub_food_category_id', $food->id)->get()->groupBy('food_header_id');
            $food['foodItems'] = $foodItemsData;
        }
        $foodItems = FoodItems::where('main_food_category_id', request()->id)->where('sub_food_category_id', null)->get()->groupBy('food_header_id');
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

    private function jsonResponse($success = false, $message = '', $data = null, $totalSubFood=0)
    {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data' => $data,
            'totalCount'=>$totalSubFood
        ]);
    }
}
