<?php

namespace App\Http\Controllers;

use App\Model\CoffeeItems;
use Illuminate\Http\Request;

class CoffeeItemsController extends Controller
{
    public function index()
    {
        $coffee = CoffeeItems::all();
        $coffee->map(function ($coffee) {
            if ($coffee->main_coffee_category_id != null) {
                $coffee->mainCoffeeCategory;
            }
        });
        if ($coffee->isNotEmpty()) {
            return $this->jsonResponse(true, 'Lists of coffees.', $coffee);
        } else {
            return $this->jsonResponse(false, 'Currently, there is no any coffee yet.', $coffee);
        }
    }

    public function getCoffeeItemList(Request $request)
    {
        $skip = $request->skip;
        $limit = $request->limit;
        $totalCoffeeItems = CoffeeItems::get()->count();

        $coffee = CoffeeItems::skip($skip)->take($limit)->orderBy('id', 'DESC')->get();
        if ($coffee->isNotEmpty()) {
            $coffee->map(function ($coffee) {
                if ($coffee->main_coffee_category_id != null) {
                    $coffee->mainCoffeeCategory;
                }
            });
            return $this->jsonResponse(true, 'Lists of coffees.', $coffee, $totalCoffeeItems);
        } else {
            return $this->jsonResponse(false, 'Currently, there is no any coffee yet.', $coffee, $totalCoffeeItems);
        }
    }

    public function store()
    {
        $coffee = CoffeeItems::create($this->validateRequest());
        return $this->jsonResponse(true, 'CoffeeItems has been created successfully.', $coffee);
    }

    public function show(CoffeeItems $coffee)
    {
        return $this->jsonResponse(true, 'Data of an individual CoffeeItems.', $coffee);
    }

    public function update(CoffeeItems $coffee)
    {
        $coffee->update($this->validateRequest());
        return $this->jsonResponse(true, 'CoffeeItems has been updated.', $coffee);
    }

    public function destroy(CoffeeItems $coffee)
    {
        $coffee->delete();
        return $this->jsonResponse(true, 'CoffeeItems has been deleted successfully.');
    }

    private function validateRequest()
    {
        return request()->validate([
            'main_coffee_category_id' => 'sometimes',
            'coffee_name' => 'required',
            'quantity' => 'sometimes',
            'price' => 'required',
        ]);
    }

    private function jsonResponse($success = false, $message = '', $data = null, $totalCoffeeItems = 0)
    {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data' => $data,
            'totalCount' => $totalCoffeeItems
        ]);
    }
}
