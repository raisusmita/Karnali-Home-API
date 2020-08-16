<?php

namespace App\Http\Controllers;

use App\Model\MainFoodCategory;
use Illuminate\Http\Request;

class MainFoodCategoryController extends Controller
{
    public function index()
    {
        $mainFood = MainFoodCategory::all();
        if ($mainFood->isNotEmpty()) {
            return $this->jsonResponse(true, 'Lists of main foods.', $mainFood);
        } else {
            return $this->jsonResponse(false, 'Currently, there is no any main food yet.', $mainFood);
        }
    }

    public function store()
    {
        $mainFood = MainFoodCategory::create($this->validateRequest());
        return $this->jsonResponse(true, 'Main Food Category has been created successfully.', $mainFood);
    }

    // public function show($id)
    // {
    //     //
    // }

    public function update(MainFoodCategory $mainFood)
    {
        $mainFood->update($this->validateRequest());
        return $this->jsonResponse(true, 'Main food category has been updated.', $mainFood);
    }
    // public function destroy(MainFoodCategory $mainFood)
    // {
    //     $mainFood->delete();
    //     return $this->jsonResponse(true, 'FoodItems has been deleted successfully.');
    // }

    private function validateRequest()
    {
        return request()->validate([
            'main_food_name' => 'required',
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
