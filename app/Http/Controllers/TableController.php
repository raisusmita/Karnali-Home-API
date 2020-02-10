<?php

namespace App\Http\Controllers;

use App\Model\Table;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TableController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $table = Table::all();
        if ($table->isNotEmpty()) {
            return response([
                'success' => true,
                'message' => 'Lists of Table.',
                'data' => $table
            ], Response::HTTP_CREATED);
        } else {
            return response([
                'success' => false,
                'message' => 'Currently, there is no any table yet.',
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
        $table = Table::create($this->validateRequest());
        return response([
            'success' => true,
            'message' => 'Table has been created successfully.',
            'data' => $table
        ], Response::HTTP_CREATED);
    }

    public function show(Table $table)
    {
        return response([
            'success' => true,
            'message' => 'Data of an individual table',
            'data' => $table
        ], Response::HTTP_CREATED);
    }

    public function update(Table $table)
    {
        $table->update($this->validateRequest());
        return response([
            'success' => true,
            'message' => 'Table has been updated',
            'data' => $table
        ], Response::HTTP_CREATED);
    }

    public function destroy(Table $table)
    {
        $table->delete();
        return response([
            'success' => true,
            'message' => 'Table has been deleted successfully.'
        ], Response::HTTP_NO_CONTENT);
    }

    public function validateRequest()
    {
        return request()->validate([
            'table_number' => 'required | unique:tables'
        ]);

    }
}
