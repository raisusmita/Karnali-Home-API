<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});




Route::apiResource('/room_categories', 'RoomCategoryController');
Route::apiResource('/rooms', 'RoomController');
Route::apiResource('/room_transactions', 'RoomTransactionController');
Route::apiResource('/reservations', 'ReservationController');
Route::apiResource('/booking', 'BookingController');
Route::apiResource('/customer', 'CustomerController');
Route::apiResource('/food', 'FoodController');
Route::apiResource('/food_orders', 'FoodOrderController');
Route::apiResource('/tables', 'TableController');
Route::apiResource('/invoices', 'InvoiceController');
Route::post('/booked_rooms', 'BookingController@storeBookedRoom');





