<?php

namespace App\Http\Controllers;

use App\Model\Table;
use Illuminate\Http\Request;

class TableController extends Controller
{

    public function index()
    {
        $totaltable = Table::get()->count();
        $table = Table::all();
        $table->map(function($itemOrderList){
            $itemOrderList->foodOrderLists = $itemOrderList->foodOrderLists()->where(['invoice_id'=>null,'status'=>'due'])->get();
            $itemOrderList->barOrderLists = $itemOrderList->barOrderLists()->where(['invoice_id'=>null,'status'=>'due'])->get();
            $itemOrderList->coffeeOrderLists = $itemOrderList->coffeeOrderLists()->where(['invoice_id'=>null,'status'=>'due'])->get();
        });
        if ($table->isNotEmpty()) {
            return $this->jsonResponse(true, 'Lists of Table.', $table, $totaltable);
        } else {
            return $this->jsonResponse(false, 'Currently, there is no any table yet.', $table, $totaltable);
        }
    }

    public function getTableList(Request $request){
        $skip =$request->skip;
        $limit=$request->limit;
        $totaltable = Table::get()->count();

        $table = Table::skip($skip)->take($limit)->orderBy('id', 'DESC')->get();
        $table->map(function($itemOrderList){
            $itemOrderList->foodOrderLists = $itemOrderList->foodOrderLists()->where(['invoice_id'=>null,'status'=>'due'])->get();
            $itemOrderList->barOrderLists = $itemOrderList->barOrderLists()->where(['invoice_id'=>null,'status'=>'due'])->get();
            $itemOrderList->coffeeOrderLists = $itemOrderList->coffeeOrderLists()->where(['invoice_id'=>null,'status'=>'due'])->get();
        });
        if ($table->isNotEmpty()) {
            return $this->jsonResponse(true, 'Lists of Table.', $table, $totaltable);
        } else {
            return $this->jsonResponse(false, 'Currently, there is no any table yet.', $table, $totaltable);
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

    private function jsonResponse($success = false, $message = '', $data = null, $totaltable=0)
    {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data' => $data,
            'totalCount'=>$totaltable
        ]);
    }
}
