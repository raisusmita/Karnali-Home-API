<?php

namespace App\Http\Controllers;

use App\Model\Table;

class TableController extends Controller
{

    public function index()
    {
        $table = Table::all();
        if ($table->isNotEmpty()) {
            return $this->jsonResponse(true, 'Lists of Table.', $table);
        } else {
            return $this->jsonResponse(false, 'Currently, there is no any table yet.', $table);
        }
    }

    public function store()
    {
        $table = Table::create($this->validateRequest());
        return $this->jsonResponse(true, 'Table has been created successfully.', $table);
    }

    public function show(Table $table)
    {
        return $this->jsonResponse(true, 'Individual table.', $table);
    }

    public function update(Table $table)
    {
        $table->update($this->validateRequest());
        return $this->jsonResponse(true, 'Table has been updated.', $table);
    }

    public function destroy(Table $table)
    {
        $table->delete();
        return $this->jsonResponse(true, 'Table has been deleted successfully.', $table);
    }

    public function validateRequest()
    {
        return request()->validate([
            'table_number' => 'required | unique:tables'
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
