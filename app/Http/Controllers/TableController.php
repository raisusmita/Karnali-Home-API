<?php

namespace App\Http\Controllers;

use App\Model\Table;

class TableController extends Controller
{

    public function index()
    {
        $table = Table::all();
        if ($table->isNotEmpty()) {
            return response()->json([
                'success' => true,
                'message' => 'Lists of Table.',
                'data' => $table
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Currently, there is no any table yet.',
            ]);
        }
    }

    public function store()
    {
        $table = Table::create($this->validateRequest());
        return response()->json([
            'success' => true,
            'message' => 'Table has been created successfully.',
            'data' => $table
        ]);
    }

    public function show(Table $table)
    {
        return response()->json([
            'success' => true,
            'message' => 'Data of an individual table',
            'data' => $table
        ]);
    }

    public function update(Table $table)
    {
        $table->update($this->validateRequest());
        return response()->json([
            'success' => true,
            'message' => 'Table has been updated',
            'data' => $table
        ]);
    }

    public function destroy(Table $table)
    {
        $table->delete();
        return response()->json([
            'success' => true,
            'message' => 'Table has been deleted successfully.'
        ]);
    }

    public function validateRequest()
    {
        return request()->validate([
            'table_number' => 'required | unique:tables'
        ]);
    }
}
