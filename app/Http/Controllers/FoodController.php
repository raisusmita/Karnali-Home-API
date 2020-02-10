<?php

namespace App\Http\Controllers;

use App\Model\Food;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FoodController extends Controller
{
    public function index()
    {
        $food = Food::all();
        if ($food->isNotEmpty()) {
            return response([
                'success' => true,
                'message' => 'Lists of Customers.',
                'data' => $food
            ], Response::HTTP_CREATED);
        } else {
            return response([
                'success' => false,
                'message' => 'Currently, there is no any Customers yet.',
            ], Response::HTTP_CREATED);
        }
    }

    public function store()
    {
        $food = Food::create($this->validateRequest());
        return response([
            'success' => true,
            'message' => 'Food has been created successfully.',
            'data' => $food
        ], Response::HTTP_CREATED);
    }

    public function show(Food $food)
    {
        return response([
            'success' => true,
            'message' => 'Data of an individual Food',
            'data' => $food
        ], Response::HTTP_CREATED);
    }

    public function update(Food $food)
    {
        $food->update($this->validateRequest());
        return response([
            'success' => true,
            'message' => 'Food has been updated',
            'data' => $food
        ], Response::HTTP_CREATED);
    }

    public function destroy(Food $food)
    {
        $food->delete();
        return response([
            'success' => true,
            'message' => 'Food has been deleted successfully.'
        ], Response::HTTP_NO_CONTENT);
    }

    private function validateRequest()
    {
        return request()->validate([
            'name' => 'required',
            'price' => 'required',
            'food_type' => 'required'
        ]);
    }
}
