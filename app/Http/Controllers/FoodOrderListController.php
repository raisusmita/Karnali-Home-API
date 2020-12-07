<?php

namespace App\Http\Controllers;

use App\Model\FoodOrderList;
use Illuminate\Support\Carbon;

class FoodOrderListController extends Controller
{

    public function index()
    {
        $foodOrderList = FoodOrderList::all();
        if ($foodOrderList->isNotEmpty()) {
            return $this->jsonResponse(true, 'Lists of Food Order.', $foodOrderList);
        } else {
            return $this->jsonResponse(false, 'Currently, there is no any Food Order yet.');
        }
    }

    public function store()
    {
        $foodOrderListData = array_map(
            function ($foodOrderListDetail) {
                $foodOrderListDetail['created_at'] = Carbon::now();
                return $foodOrderListDetail;
            },
            request()->all()
        );
        $foodOrderList = FoodOrderList::insert($foodOrderListData);
        return $this->jsonResponse(true, 'Food Order has been created successfully.', $foodOrderList);
    }

    public function show(FoodOrderList $foodOrderList)
    {
        return $this->jsonResponse(true, 'Data of an individual Food Order.', $foodOrderList);
    }

    public function foodOrderListDetails()
    {
        $foodOrderList = FoodOrderList::all();
        if ($foodOrderList->isNotEmpty()) {
            return $this->jsonResponse(true, 'Lists of Food Order.', $foodOrderList);
        } else {
            return $this->jsonResponse(false, 'Currently, there is no any Food Order yet.');
        }
    }

    // public function update(FoodOrderList $foodOrderList)
    // {
    //     $foodOrderList->update($this->validateRequest());
    //     return $this->jsonResponse(true, 'FoodOrderList has been updated.', $foodOrderList);
    // }

    public function destroy(FoodOrderList $foodOrderList)
    {
        $foodOrderList->delete();
        return $this->jsonResponse(true, 'Food Order has been deleted successfully.');
    }

    public function validateRequest()
    {
        return request()->validate([
            '*.food_items_id' => 'required',
            '*.room_id' => 'required_without:*.table_id',
            '*.table_id' => 'required_without:*.room_id',
            '*.invoice_id' => 'nullabel|sometimes',
            '*.quantity' => 'required',
            '*.price' => 'required',
            '*.total_amount' => 'required',

            // '*.created_at' => 'required',
            // '*.updated_at' => 'required',

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
