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

    public function getMainFoodList(Request $request){
        $skip =$request->skip;
        $limit=$request->limit;
        $totalMainFood = MainFoodCategory::get()->count();

        $mainFood = MainFoodCategory::skip($skip)->take($limit)->get();
        if ($mainFood->isNotEmpty()) {
            return $this->jsonResponse(true, 'Lists of main foods.', $mainFood, $totalMainFood);
        } else {
            return $this->jsonResponse(false, 'Currently, there is no any main food yet.', $mainFood, $totalMainFood);
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

    private function jsonResponse($success = false, $message = '', $data = null, $totalMainFood =0)
    {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data' => $data,
            'totalCount'=>$totalMainFood
        ]);
    }
}
