<?php

namespace App\Http\Controllers;

use App\Model\FoodOrderList;
use App\Model\Reservation;
use App\Model\Room;
use App\Model\RoomAvailability;
use App\Model\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function fetchAvailability()
    {
        $counts = [];
        $unAvailableRoom = RoomAvailability::unavailable()->get();
        $roomIds = [];
        foreach ($unAvailableRoom as $unAvailable) {
            array_push($roomIds, $unAvailable->room_id);
        }
        $counts['totalAvailableRoom'] = Room::whereNotIn('id', $roomIds)->count();
        $counts['totalReservation'] = RoomAvailability::unavailable()->where('status', 'reserved')->count();
        $counts['totalBooking'] = RoomAvailability::unavailable()->where('status', 'booked')->count();
        $adult = Reservation::where('status', 'active')->sum('number_of_adult');
        $child = Reservation::where('status', 'active')->sum('number_of_child');
        $counts['totalNumberOfGuest'] = $adult + $child;

        return $this->jsonResponse(true, 'Available data', $counts);
    }

    public function fetchTotalRevenue()
    {
        $revenue_today = [];
        $revenue_today['food'] = DB::table('food_order_lists')->whereDate('created_at', Carbon::today())->where('status', 'paid')->sum('total_amount');
        $revenue_today['bar'] = DB::table('bar_order_lists')->whereDate('created_at', Carbon::today())->where('status', 'paid')->sum('total_amount');
        $revenue_today['coffee'] = DB::table('coffee_order_lists')->whereDate('created_at', Carbon::today())->where('status', 'paid')->sum('total_amount');
        $revenue_today['room'] = DB::table('room_transactions')->whereDate('created_at', Carbon::today())->sum('total_amount');
        return $this->jsonResponse(true, 'Total Revenue Details', $revenue_today);
    }

    public function fetchOrderDetails()
    {
        $orderStatus = [];
        $foodOrdered = DB::table('food_order_lists')->whereDate('created_at', Carbon::today())->where('order_status', 'ordered')->count();
        $barOrdered = DB::table('bar_order_lists')->whereDate('created_at', Carbon::today())->where('order_status', 'ordered')->count();
        $coffeeOrdered = DB::table('coffee_order_lists')->whereDate('created_at', Carbon::today())->where('order_status', 'ordered')->count();
        $orderStatus['TotalOrdered'] = $foodOrdered + $barOrdered + $coffeeOrdered;
        $foodCompleted = DB::table('food_order_lists')->whereDate('created_at', Carbon::today())->where('order_status', 'completed')->count();
        $barCompleted = DB::table('bar_order_lists')->whereDate('created_at', Carbon::today())->where('order_status', 'completed')->count();
        $coffeeCompleted = DB::table('coffee_order_lists')->whereDate('created_at', Carbon::today())->where('order_status', 'completed')->count();
        $orderStatus['TotalCompleted'] = $foodCompleted + $barCompleted + $coffeeCompleted;
        return $this->jsonResponse(true, 'Total Ordered and completed list', $orderStatus);
    }

    public function fetchAvailableTables()
    {
        $foodTables = collect(DB::table('food_order_lists')->where('status', 'due')->whereNotNull('table_id')->groupBy('table_id')->pluck('table_id'));
        $barTables = collect(DB::table('bar_order_lists')->where('status', 'due')->whereNotNull('table_id')->groupBy('table_id')->pluck('table_id'));
        $coffeeTables = collect(DB::table('coffee_order_lists')->where('status', 'due')->whereNotNull('table_id')->groupBy('table_id')->pluck('table_id'));
        $table = collect(Table::pluck('id')->toArray());
        $availableTable = $table->diff(array_unique(array_merge($foodTables->toArray(), $barTables->toArray(), $coffeeTables->toArray())));
        $tables = Table::whereIn('id', $availableTable)->get();
        return $this->jsonResponse(true, 'Total Available Table', $tables);
    }

    private function jsonResponse($success = false, $message = '', $data = null)
    {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data' => $data,
        ]);
    }
}
