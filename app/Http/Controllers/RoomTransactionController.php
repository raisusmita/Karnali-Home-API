<?php

namespace App\Http\Controllers;

use App\Model\RoomTransaction;


class RoomTransactionController extends Controller
{
    public function index()
    {
        $roomTransaction = RoomTransaction::all();
        if ($roomTransaction->isNotEmpty()) {
            return response()->json([
                'success' => true,
                'message' => 'Lists of Room Transactions.',
                'data' => $roomTransaction
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Currently, there is no any Room Transactions yet.',
            ]);
        }
    }

    public function store()
    {
        $roomTransaction = RoomTransaction::create($this->validateRequest());
        return response()->json([
            'success' => true,
            'message' => 'Room Transaction has been created successfully.',
            'data' => $roomTransaction
        ]);
    }

    public function show(RoomTransaction $roomTransaction)
    {
        return response()->json([
            'success' => true,
            'message' => 'Data of an individual roomTransaction',
            'data' => $roomTransaction
        ]);
    }

    public function update(RoomTransaction $roomTransaction)
    {
        $roomTransaction->update($this->validateRequest());
        return response()->json([
            'success' => true,
            'message' => 'Room Transaction has been updated',
            'data' => $roomTransaction
        ]);
    }

    public function destroy(RoomTransaction $roomTransaction)
    {
        $roomTransaction->delete();
        return response()->json([
            'success' => true,
            'message' => 'Room Transaction has been deleted successfully.'
        ]);
    }

    public function validateRequest()
    {
        return request()->validate([
            'customer_id' => 'required',
            'reservation_id' => 'required',
            'invoice_id' => 'nullable',
            'number_of_days' => 'required',
            'rate' => 'required',
            'total_amount' => 'required'
        ]);
    }
}
