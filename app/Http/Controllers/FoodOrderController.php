<?php

namespace App\Http\Controllers;

use App\Model\FoodOrder;

class FoodOrderController extends Controller
{

    public function index()
    {
        $foodOrder = FoodOrder::all();
        if ($foodOrder->isNotEmpty()) {
            return response()->json([
                'success' => true,
                'message' => 'Lists of Food Order.',
                'data' => $foodOrder
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Currently, there is no any Food Order yet.',
            ]);
        }
    }

    public function store()
    {
        $foodOrder = FoodOrder::create($this->validateRequest());
        return response()->json([
            'success' => true,
            'message' => 'Food Order has been created successfully.',
            'data' => $foodOrder
        ]);
    }

    public function show(FoodOrder $foodOrder)
    {
        return response()->json([
            'success' => true,
            'message' => 'Data of an individual Food Order',
            'data' => $foodOrder
        ]);
    }

    public function update(FoodOrder $foodOrder)
    {
        $foodOrder->update($this->validateRequest());
        return response()->json([
            'success' => true,
            'message' => 'Food Order has been updated',
            'data' => $foodOrder
        ]);
    }

    public function destroy(FoodOrder $foodOrder)
    {
        $foodOrder->delete();
        return response()->json([
            'success' => true,
            'message' => 'Food Order has been deleted successfully.'
        ]);
    }

    public function validateRequest()
    {
        return request()->validate([
            'food_id' => 'required',
            'reservation_id' => 'nullable|sometimes',
            'table_id' => 'nullable|sometimes',
            'invoice_id' => 'nullabel|sometimes',
            'quantity' => 'required',
            'price' => 'required',
            'total_amount' => 'required',
        ]);
    }
}
