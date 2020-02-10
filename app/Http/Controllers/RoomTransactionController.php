<?php

namespace App\Http\Controllers;

use App\Model\RoomTransaction;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


class RoomTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roomTransaction = RoomTransaction::all();
        if ($roomTransaction->isNotEmpty()) {
            return response([
                'success' => true,
                'message' => 'Lists of Room Transactions.',
                'data' => $roomTransaction
            ], Response::HTTP_CREATED);
        } else {
            return response([
                'success' => false,
                'message' => 'Currently, there is no any Room Transactions yet.',
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
        $roomTransaction = RoomTransaction::create($this->validateRequest());
        return response([
            'success' => true,
            'message' => 'Room Transaction has been created successfully.',
            'data' => $roomTransaction
        ], Response::HTTP_CREATED);
    }

    public function show(RoomTransaction $roomTransaction)
    {
        return response([
            'success' => true,
            'message' => 'Data of an individual roomTransaction',
            'data' => $roomTransaction
        ], Response::HTTP_CREATED);
    }

    public function update(RoomTransaction $roomTransaction)
    {
        $roomTransaction->update($this->validateRequest());
        return response([
            'success' => true,
            'message' => 'Room Transaction has been updated',
            'data' => $roomTransaction
        ], Response::HTTP_CREATED);
    }

    public function destroy(RoomTransaction $roomTransaction)
    {
        $roomTransaction->delete();
        return response([
            'success' => true,
            'message' => 'Room Transaction has been deleted successfully.'
        ], Response::HTTP_NO_CONTENT);
    }

    public function validateRequest()
    {
        return request()->validate([
            'customer_id' => 'required',
            'reservation_id' => 'required',
            'number_of_day' => 'required',
            'rate' => 'required',
            'total_amount' => 'required',
            'transaction_date' => 'required'
        ]);

    }
}
