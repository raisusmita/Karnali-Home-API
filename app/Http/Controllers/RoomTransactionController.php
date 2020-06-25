<?php

namespace App\Http\Controllers;

use App\Model\RoomTransaction;


class RoomTransactionController extends Controller
{
    public function index()
    {
        $roomTransaction = RoomTransaction::all();
        if ($roomTransaction->isNotEmpty()) {
            return $this->jsonResponse(true, 'Lists of Room Transactions.', $roomTransaction);
        } else {
            return $this->jsonResponse(false, 'Currently, there is no any Room Transactions yet.', $roomTransaction);
        }
    }

    public function store()
    {
        $roomTransaction = RoomTransaction::create($this->validateRequest());
        return $this->jsonResponse(true, 'Room Transaction has been created successfully.', $roomTransaction);
    }

    public function show(RoomTransaction $roomTransaction)
    {
        return $this->jsonResponse(true, 'Data of an individual room transaction.', $roomTransaction);
    }

    public function update(RoomTransaction $roomTransaction)
    {
        $roomTransaction->update($this->validateRequest());
        return $this->jsonResponse(true, 'Room Transaction has been updated.', $roomTransaction);
    }

    public function destroy(RoomTransaction $roomTransaction)
    {
        $roomTransaction->delete();
        return $this->jsonResponse(true, 'Room Transaction has been deleted successfully.');
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

    private function jsonResponse($success = false, $message = '', $data = null)
    {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data' => $data
        ]);
    }
}
