<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('cors')->group(function () {
    //your_routes
});

Route::post('/login', 'UserController@login');

Route::group(['middleware' => 'auth:api'], function () {
    Route::apiResource('/room_categories', 'RoomCategoryController');
    Route::apiResource('/rooms', 'RoomController');
    Route::post('/room_category/room', 'RoomController@getRoomBasedOnCategory');
    Route::apiResource('/room_transactions', 'RoomTransactionController');

    Route::apiResource('/reservations', 'ReservationController');
    Route::apiResource('/booking', 'BookingController');
    Route::apiResource('/customer', 'CustomerController');
    Route::apiResource('/food', 'FoodItemsController');
    Route::apiResource('/food_orders', 'FoodOrderController');
    Route::apiResource('/tables', 'TableController');
    Route::apiResource('/invoices', 'InvoiceController');
    Route::apiResource('/user', 'UserController');
    Route::apiResource('/mainFood', 'MainFoodCategoryController');
    Route::apiResource('/subFood', 'SubFoodCategoryController');
    Route::apiResource('/foodHeader', 'FoodHeaderController');

    Route::apiResource('/mainBar', 'MainBarCategoryController');
    Route::apiResource('/subBar', 'SubBarCategoryController');
    Route::apiResource('/bar', 'BarItemsController');


    Route::post('/booked_rooms', 'BookingController@storeBookedRoom');
    Route::post('/bookingCancelled', 'BookingController@bookingCancelled');
    Route::get('/booked_rooms', 'BookingController@getBookedRoom');
    Route::get('/booked_rooms/{{id}}', 'BookingController@showBookedRoom');
    Route::post('/editRoomCategory', 'RoomCategoryController@editRoomCategory');
    Route::post('/editCustomer', 'CustomerController@editCustomer');
    Route::post('/editRoomTransaction', 'RoomTransactionController@updateRoomTransaction');
    Route::get('/activeBooking', 'BookingController@getActiveBooking');



    Route::get('/available', 'RoomAvailabilityController@getAvailableRoom');
    Route::get('/unavailable', 'RoomAvailabilityController@getUnavailableRoom');
    Route::post('/availableRoomByDate', 'RoomAvailabilityController@getAvailableRoomByDate');
    Route::post('/availableRoomByBookingId', 'RoomAvailabilityController@getRoomByBookingId');
    Route::post('/roomListByCustomerId', 'RoomAvailabilityController@getRoomByCustomerId');
    Route::post('/availableRoomByBooking', 'RoomAvailabilityController@storeRoomAvailability');
    Route::post('/bookingToReservation', 'RoomAvailabilityController@updateBookingToReservation');
});
