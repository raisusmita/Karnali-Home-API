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
    Route::apiResource('/rooms', 'RoomController');
    Route::post('/room_category/room', 'RoomController@getRoomBasedOnCategory');
    Route::apiResource('/room_transactions', 'RoomTransactionController');

    Route::apiResource('/reservations', 'ReservationController');
    Route::apiResource('/booking', 'BookingController');
    Route::apiResource('/customer', 'CustomerController');
    Route::apiResource('/food', 'FoodItemsController');
    Route::apiResource('/foodOrderList', 'FoodOrderListController');
    Route::apiResource('/foodOrder', 'FoodOrderController');

    Route::apiResource('/tables', 'TableController');
    Route::apiResource('/invoices', 'InvoiceController');
    Route::apiResource('/user', 'UserController');
    Route::apiResource('/mainFood', 'MainFoodCategoryController');
    Route::apiResource('/subFood', 'SubFoodCategoryController');
    Route::post('/subFoodById', 'SubFoodCategoryController@getSubAndFoodItemsById');
    Route::post('/barItemById', 'BarItemsController@getBarItemsById');
    Route::post('/coffeeItemById', 'CoffeeItemsController@getCoffeeItemsById');

    // subFoodById

    Route::apiResource('/mainBar', 'MainBarCategoryController');
    Route::apiResource('/mainCoffee', 'MainCoffeeCategoryController');

    Route::apiResource('/bar', 'BarItemsController');
    Route::apiResource('/coffee', 'CoffeeItemsController');

    Route::post('/booked_rooms', 'BookingController@storeBookedRoom');
    Route::post('/bookingCancelled', 'BookingController@bookingCancelled');
    Route::get('/booked_rooms', 'BookingController@getBookedRoom');
    Route::get('/booked_rooms/{{id}}', 'BookingController@showBookedRoom');
    Route::post('/editRoomCategory', 'RoomCategoryController@editRoomCategory');
    Route::post('/editCustomer', 'CustomerController@editCustomer');
    Route::post('/editRoomTransaction', 'RoomTransactionController@updateRoomTransaction');
    Route::get('/activeBooking', 'BookingController@getActiveBooking');

    // Get data based on pagination
    Route::post('/bookingList', 'BookingController@getBookingList');
    Route::post('/roomCategoryList', 'RoomCategoryController@getRoomCategoryList');
    Route::post('/roomList', 'RoomController@getRoomList');
    Route::post('/customerList', 'CustomerController@getCustomerList');
    Route::post('/reservationList', 'ReservationController@getReservationList');
    Route::post('/foodItemList', 'FoodItemsController@getFoodItemList');
    Route::post('/mainFoodList', 'MainFoodCategoryController@getMainFoodList');
    Route::post('/subFoodList', 'SubFoodCategoryController@getSubFoodList');
    Route::post('/barItemList', 'BarItemsController@getBarItemList');
    Route::post('/coffeeItemList', 'CoffeeItemsController@getCoffeeItemList');
    Route::post('/mainBarList', 'MainBarCategoryController@getMainBarList');
    Route::post('/mainCoffeeList', 'MainCoffeeCategoryController@getMainCoffeeList');
    Route::post('/tableList', 'TableController@getTableList');
    Route::post('/roomTransactionList', 'RoomTransactionController@getRoomTransactionList');
    Route::post('/roomTransactionDetailByRoomId', 'RoomTransactionController@getRoomTransactionDetailByRoomId');
    Route::post('/foodDetailForRoom', 'RoomTransactionController@getFoodDetailForRoom');
    Route::post('/foodDetailForTable', 'RoomTransactionController@getFoodDetailForTable');

    Route::post('/userList', 'UserController@getUserList');
    Route::post('/invoiceList', 'InvoiceController@getInvoiceList');
    Route::post('/invoiceDetail', 'InvoiceController@invoiceDetail');

    Route::get('/available', 'RoomAvailabilityController@getAvailableRoom');
    Route::get('/unavailable', 'RoomAvailabilityController@getUnavailableRoom');
    Route::post('/availableRoomByBookingId', 'RoomAvailabilityController@getRoomByBookingId');
    Route::post('/roomListByCustomerId', 'RoomAvailabilityController@getRoomDetailByCustomerId');
    Route::post('/availableRoomByBooking', 'RoomAvailabilityController@storeRoomAvailability');
    Route::post('/bookingToReservation', 'RoomAvailabilityController@updateBookingToReservation');
});


Route::apiResource('/room_categories', 'RoomCategoryController');
Route::post('/availableRoomByDate', 'RoomAvailabilityController@getAvailableRoomByDate');
Route::post('/multipleBooking', 'BookingController@storeMultipleBooking');



