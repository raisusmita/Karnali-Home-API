<?php

namespace App\Http\Controllers;

use App\Model\FoodOrder;
use Illuminate\Support\Carbon;

class FoodOrderController extends Controller
{

    public function index()
    {
        $foodOrder = FoodOrder::all();
        if ($foodOrder->isNotEmpty()) {
            return $this->jsonResponse(true, 'Lists of Food Order.', $foodOrder);
        } else {
            return $this->jsonResponse(false, 'Currently, there is no any Food Order yet.');
        }
    }

    public function store()
    {
        $foodOrderData = array_map(
            function ($foodOrderDetail) {
                $foodOrderDetail['created_at'] =Carbon::now();
                return $foodOrderDetail;
            }, request()->all()
        );
        $foodOrder = FoodOrder::insert($foodOrderData);
        return $this->jsonResponse(true, 'Food Order has been created successfully.', $foodOrder);
    }

    public function show(FoodOrder $foodOrder)
    {
        return $this->jsonResponse(true, 'Data of an individual Food Order.', $foodOrder);
    }

    // public function update(FoodOrder $foodOrder)
    // {
    //     $foodOrder->update($this->validateRequest());
    //     return $this->jsonResponse(true, 'FoodOrder has been updated.', $foodOrder);
    // }

    public function destroy(FoodOrder $foodOrder)
    {
        $foodOrder->delete();
        return $this->jsonResponse(true, 'Food Order has been deleted successfully.');
    }

    public function validateRequest()
    {
        return request()->validate([
            '*.food_items_id' => 'required',
            '*.reservation_id' => 'nullable|sometimes',
            '*.table_id' => 'nullable|sometimes',
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
