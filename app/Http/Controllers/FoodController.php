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
        return $food;
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {

        $food = Food::create($this->validateRequest());
        return response([
            'data' => $food
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Food $food)
    {
        //
        return $food;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Food $food)
    {
        //
        $food->update($this->validateRequest());
        return response([
            'data' => $food
        ], Response::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Food $food)
    {
        //
        $food->delete();
        return response('Food Deleted SUccessfully', Response::HTTP_NO_CONTENT);
    }

    private function validateRequest()
    {
        return request()->validate([
            'name'=>'required',
            'price'=>'required',
            'food_type'=>'required'
        ]);
    }
}
