<?php

namespace App\Http\Controllers;

use App\Model\MainCoffeeCategory;
use Illuminate\Http\Request;

class MainCoffeeCategoryController extends Controller
{
    public function index()
    {
        $mainCoffee = MainCoffeeCategory::all();
        if ($mainCoffee->isNotEmpty()) {
            return $this->jsonResponse(true, 'Lists of main coffee.', $mainCoffee);
        } else {
            return $this->jsonResponse(false, 'Currently, there is no any main coffee yet.', $mainCoffee);
        }
    }

    public function getMainCoffeeList(Request $request)
    {
        $skip = $request->skip;
        $limit = $request->limit;
        $totalMainCoffee = MainCoffeeCategory::get()->count();

        $mainCoffee = MainCoffeeCategory::skip($skip)->take($limit)->orderBy('id', 'DESC')->get();
        if ($mainCoffee->isNotEmpty()) {
            return $this->jsonResponse(true, 'Lists of main coffee.', $mainCoffee, $totalMainCoffee);
        } else {
            return $this->jsonResponse(false, 'Currently, there is no any main coffee yet.', $mainCoffee, $totalMainCoffee);
        }
    }

    public function store()
    {
        $mainCoffee = MainCoffeeCategory::create($this->validateRequest());
        return $this->jsonResponse(true, 'Main Coffee Category has been created successfully.', $mainCoffee);
    }

    // public function show($id)
    // {
    //     //
    // }

    public function update(MainCoffeeCategory $mainCoffee)
    {
        $mainCoffee->update($this->validateRequest());
        return $this->jsonResponse(true, 'Main coffee category has been updated.', $mainCoffee);
    }
    // public function destroy(MainCoffeeCategory $mainCoffee)
    // {
    //     $mainCoffee->delete();
    //     return $this->jsonResponse(true, 'CoffeeItems has been deleted successfully.');
    // }

    private function validateRequest()
    {
        return request()->validate([
            'main_coffee_name' => 'required',
        ]);
    }

    private function jsonResponse($success = false, $message = '', $data = null, $totalMainCoffee = 0)
    {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data' => $data,
            'totalCount' => $totalMainCoffee
        ]);
    }
}
