<?php

namespace App\Http\Controllers;

use App\Model\FoodOrder;
use App\Model\FoodOrderList;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class FoodOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->skip && $request->limit) {
            $skip = $request->skip;
            $limit = $request->limit;
        } else {
            $skip = 0;
            $limit = 10;
        }
        $totalFoodItem = FoodOrderList::get()->count();
        $foodOrder = FoodOrder::skip($skip)->take($limit)->get();
        $foodOrder->map(function ($foodOrderItem) {
            $foodOrderItem->FoodOrderLists->map(function ($foodItem) {
                $foodItem->FoodItems;
                if ($foodItem->room_id) {
                    $foodItem->Room;
                } else {
                    $foodItem->Table;
                }
            });
        });
        if ($foodOrder->isNotEmpty()) {
            return $this->jsonResponse(true, 'Lists of foods Orders.', $foodOrder, $totalFoodItem);
        } else {
            return $this->jsonResponse(false, 'Currently, there is no any food order.', $foodOrder, $totalFoodItem);
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
        $orderId = FoodOrder::create()->id;
        $foodOrderListData = array_map(
            function ($foodOrderListDetail) use ($orderId) {
                $foodOrderListDetail['food_order_id'] = $orderId;
                $foodOrderListDetail['created_at'] = Carbon::now();
                return $foodOrderListDetail;
            },
            request()->all()
        );
        $foodOrderList = FoodOrderList::insert($foodOrderListData);
        return $this->jsonResponse(true, 'Food Order has been created successfully.', $foodOrderList);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\FoodOrder  $foodOrder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FoodOrder $foodOrder)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\FoodOrder  $foodOrder
     * @return \Illuminate\Http\Response
     */
    public function destroy(FoodOrder $foodOrder)
    {
        $foodOrder->delete();
        return $this->jsonResponse(true, 'Food Order has been deleted successfully.');
    }

    private function validateOrderItemRequest()
    {
        return request()->validate([
            '*.food_order_id' => 'required',
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

    private function jsonResponse($success = false, $message = '', $data = null, $totalFoodItem = 0)
    {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data' => $data,
            'totalCount' => $totalFoodItem
        ]);
    }
}
