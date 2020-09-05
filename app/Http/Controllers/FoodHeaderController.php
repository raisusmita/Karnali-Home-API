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

    public function getFoodHeaderList(Request $request){
        $skip =$request->skip;
        $limit=$request->limit;
        $totalFoodHeader = FoodHeader::get()->count();

        $foodHeader = FoodHeader::skip($skip)->take($limit)->get();
        if ($foodHeader->isNotEmpty()) {
            return $this->jsonResponse(true, 'Lists of foods headers.', $foodHeader, $totalFoodHeader);
        } else {
            return $this->jsonResponse(false, 'Currently, there is no any food headers yet.', $foodHeader, $totalFoodHeader);
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
            'food_header' => 'required',
        ]);
    }

    private function jsonResponse($success = false, $message = '', $data = null, $totalFoodHeader=0)
    {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data' => $data,
            'totalCount'=>$totalFoodHeader
        ]);
    }
}
