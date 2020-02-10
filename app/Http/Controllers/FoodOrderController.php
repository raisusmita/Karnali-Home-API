<?php

namespace App\Http\Controllers;

use App\Model\FoodOrder;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FoodOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $foodOrder = FoodOrder::all();
        if ($foodOrder->isNotEmpty()) {
            return response([
                'success' => true,
                'message' => 'Lists of Food Order.',
                'data' => $foodOrder
            ], Response::HTTP_CREATED);
        } else {
            return response([
                'success' => false,
                'message' => 'Currently, there is no any Food Order yet.',
            ], Response::HTTP_CREATED);
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $foodOrder = FoodOrder::create($this->validateRequest());
        return response([
            'success' => true,
            'message' => 'Food Order has been created successfully.',
            'data' => $foodOrder
        ], Response::HTTP_CREATED);
    }

    public function show(FoodOrder $foodOrder)
    {
        return response([
            'success' => true,
            'message' => 'Data of an individual Food Order',
            'data' => $foodOrder
        ], Response::HTTP_CREATED);
    }

    public function update(FoodOrder $foodOrder)
    {
        $foodOrder->update($this->validateRequest());
        return response([
            'success' => true,
            'message' => 'Food Order has been updated',
            'data' => $foodOrder
        ], Response::HTTP_CREATED);
    }

    public function destroy(FoodOrder $foodOrder)
    {
        $foodOrder->delete();
        return response([
            'success' => true,
            'message' => 'Food Order has been deleted successfully.'
        ], Response::HTTP_NO_CONTENT);
    }

    public function validateRequest()
    {
        return request()->validate([
            'food_id' => 'required',
            'reservation_id' =>'required',
            'table_id' =>'required',
            'invoice_id' => 'required',
            'quantity' => 'required',
            'price' => 'required',
            'total_amount' =>'required',
            'order_date' => 'required'
        ]);


    }
}
