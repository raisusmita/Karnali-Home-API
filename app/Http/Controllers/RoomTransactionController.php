<?php

namespace App\Http\Controllers;

use App\Model\RoomTransaction;
use App\Model\Room;
use App\Model\FoodOrderList;
use App\Model\CoffeeOrderList;
use App\Model\BarOrderList;
use Illuminate\Http\Request;
use App\Model\RoomCategory;
use App\Model\Reservation;
use App\Model\RoomAvailability;
use App\Model\Customer;
use App\Model\BarItems;
use App\Model\BarName;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;



class RoomTransactionController extends Controller
{
    public function index()
    {
        $roomTransaction = RoomTransaction::orderBy('id', 'DESC')->get();
        if ($roomTransaction->isNotEmpty()) {
            $roomTransaction->map(function($roomTransaction){
                $roomTransaction->reservation->room->roomCategory;
                $roomTransaction->customer;
            });
            return $this->jsonResponse(true, 'Lists of Room Transactions.', $roomTransaction);
        } else {
            return $this->jsonResponse(false, 'Currently, there is no any Room Transactions yet.', $roomTransaction);
        }
    }

    public function getRoomTransactionList(Request $request){
        $skip =$request->skip;
        $limit=$request->limit;
        $totalRoomTransaction = RoomTransaction::get()->count();

        $roomTransaction = RoomTransaction::skip($skip)->take($limit)->orderBy('id', 'DESC')->get();
        if ($roomTransaction->isNotEmpty()) {
            $roomTransaction->map(function($roomTransaction){
                $roomTransaction->reservation->room->roomCategory;
                $roomTransaction->customer;
                $roomTransaction->invoice;

            });
            return $this->jsonResponse(true, 'Lists of Room Transactions.', $roomTransaction, $totalRoomTransaction);
        } else {
            return $this->jsonResponse(false, 'Currently, there is no any Room Transactions yet.', $roomTransaction, $totalRoomTransaction);
        }
    }

    
    public function getRoomTransactionDetailByRoomId(Request $request){
        $roomId = $request->roomId;
        
        $room = Room::where(['id'=>$roomId])->get();
        if ($room->isNotEmpty()) {
            $room->map(function($room){
                $room->roomCategory;
                
            });
            return $this->jsonResponse(true, 'Lists of Room Transactions.', $roomTransaction, $totalRoomTransaction);
        } else {
            return $this->jsonResponse(false, 'Currently, there is no any Room Transactions yet.', $roomTransaction, $totalRoomTransaction);
        }
    }


    public function store(Request $request)

    {
        try{
            DB::beginTransaction();
            $roomTransaction = $request->all();
            $transactionDetail =[];
            $allTransactionDetail=[];
            foreach($roomTransaction as $roomDetail){
                // Get room category detail for price
                $roomCategory = RoomCategory::where(['id'=>$roomDetail['room_category_id']])->get();
                // Get reservation detail for customer id
                $customer = Reservation::where(['id'=>$roomDetail['reservation_id']])->get();
                // Subtracting todayDate from checkInDate to calculate number of days stayed
                $checkInDate = new \DateTime($roomDetail['check_in_date']);
                $checkOutDate = new \DateTime($roomDetail['check_out_date']);
                $interval = $checkOutDate->diff($checkInDate);
                $days = $interval->format('%a');
                // Params for room transaction
                $roomTransactionParams =  array(
                    "customer_id" => $customer[0]['customer_id'],
                    "reservation_id" => $roomDetail['reservation_id'],
                    "number_of_days" =>$days,
                    "rate" => $roomCategory[0]['room_price'],
                    "total_amount" => $roomCategory[0]['room_price'] * $days,
                    "invoice_id"=>null,
                );
                $roomTransaction = RoomTransaction::create($roomTransactionParams);
                $customerData=Customer::where(['id'=>$roomTransaction->customer_id])->get();
                $reservationData =Reservation::where(['id'=>$roomTransaction->reservation_id])->get();
                $reservationData->map(function($reservation){
                    $reservation->Room;
                });
                 // Params for room transaction
                 $transactionDetail =  array(
                    "customer" =>$customerData,
                    "reservation" =>$reservationData,
                    "transaction"=>$roomTransaction,
                    "callFrom"=>"transaction"
                );
                array_push($allTransactionDetail, $transactionDetail);
                // Update roomAvailability info
                $roomAvailability= RoomAvailability::where(['reservation_id'=> $roomDetail['reservation_id'], 'room_id'=>$roomDetail['room_id']])->update([
                    "status"=>"transact",
                    "check_in_date"=> Carbon::createFromFormat('Y-m-d\TH:i:s+', $roomDetail['check_in_date']),
                    "check_out_date"=> Carbon::createFromFormat('Y-m-d\TH:i:s+', $roomDetail['check_out_date']),
                    "availability"=>'0'
                ]);
                // Update reservation checkIn/checkOut Date
                $roomAvailability= Reservation::where(['id'=> $roomDetail['reservation_id']])->update([
                    "check_in_date"=> Carbon::createFromFormat('Y-m-d\TH:i:s+', $roomDetail['check_in_date']),
                    "check_out_date"=> Carbon::createFromFormat('Y-m-d\TH:i:s+', $roomDetail['check_out_date']),
                    "status"=>'complete'
                ]);
            };
            DB::commit();
            return $this->jsonResponse(true, 'Room Transaction has been created successfully.', $allTransactionDetail);
        }
        catch(\Exception $e)
        {
            DB::rollback();
        }
    }

    public function getFoodDetailForRoom(Request $request){
        $params = $request->all();
        $roomId = $params['roomId'];
        $reservationId = $params['reservationId'];
        $callFrom = $params['callFrom'];
        $reservation = Reservation::where('id',$reservationId)->get();
        $checkInDate = $reservation[0]->check_in_date;
        $checkOutDate = $reservation[0]->check_out_date;
        if($callFrom =='afterProcessing'){
             //Get FoodOderList
            $foodOrderList = FoodOrderList::where('room_id', $roomId)->
            whereBetween('created_at', [$checkInDate, $checkOutDate])->whereNotNull('invoice_id')->get();
            $foodOrderList->map(function ($order){
                $order->FoodItems;
            });
            //Get CoffeeOrderList
            $coffeeOrderList = CoffeeOrderList::where('room_id', $roomId)->
            whereBetween('created_at', [$checkInDate, $checkOutDate])->whereNotNull('invoice_id')->get();
            $coffeeOrderList->map(function ($order){
                $order->CoffeeItems;
            });
            //Get BarOrderList
            $barOrderList = BarOrderList::where('room_id', $roomId)->
            whereBetween('created_at', [$checkInDate, $checkOutDate])->whereNotNull('invoice_id')->get();
            $barOrderList->map(function ($order){
                $order->BarItems;
                $BarNameId = BarItems::find($order->bar_items_id)->bar_name_id;
                $BarName = BarName::find($BarNameId)->bar_name;
                $order['bar_name'] =$BarName;
            });
            // toBase() is used to restrict the remove of multiple object having same id during merge
            $firstMergeOrderList = $foodOrderList->toBase()->merge($coffeeOrderList);
            $allOrderList = $firstMergeOrderList->toBase()->merge($barOrderList);
        }else{
            //Get FoodOderList
            $foodOrderList = FoodOrderList::where('room_id', $roomId)->
            whereBetween('created_at', [$checkInDate, $checkOutDate])->where('invoice_id',null)->get();
            $foodOrderList->map(function ($order){
                $order->FoodItems;
            });
            //Get CoffeeOrderList
            $coffeeOrderList = CoffeeOrderList::where('room_id', $roomId)->
            whereBetween('created_at', [$checkInDate, $checkOutDate])->where('invoice_id', null)->get();
            $coffeeOrderList->map(function ($order){
                $order->CoffeeItems;
              });
            //Get BarOrderList
            $barOrderList = BarOrderList::where('room_id', $roomId)->
            whereBetween('created_at', [$checkInDate, $checkOutDate])->where('invoice_id', null)->get();
            $barOrderList->map(function ($order){
                $order->BarItems;
                $BarNameId = BarItems::find($order->bar_items_id)->bar_name_id;
                $BarName = BarName::find($BarNameId)->bar_name;
                $order['bar_name'] =$BarName;
            });
            // toBase() is used to restrict the remove of multiple object having same id during merge
            $firstMergeOrderList = $foodOrderList->toBase()->merge($coffeeOrderList);
            $allOrderList = $firstMergeOrderList->toBase()->merge($barOrderList);
        }
        return $allOrderList;
        //   if ($foodOrder->isNotEmpty()) {
        //     return $this->jsonResponse(true, 'List of Food Order made by room.', $foodOrder);
        // } else {
        //     return $this->jsonResponse(false, 'Currently, there is no any food order.', $foodOrder);
        // }
    }

    public function getFoodDetailForTable(Request $request){
        $params = $request->all();
        $tableId = $params['table_id'];

        //Get FoodOrderList
        $foodOrderList = FoodOrderList::where(['table_id'=>$tableId, 'status'=>'due'])->get();
        $foodOrderList->map(function ($order){
            $order->FoodItems;
        });

        //Get CoffeeOrderList
        $coffeeOrderList = CoffeeOrderList::where(['table_id'=>$tableId, 'status'=>'due'])->get();
        $coffeeOrderList->map(function ($order){
            $order->CoffeeItems;
        });

        //Get BarOrderList
        $barOrderList = BarOrderList::where(['table_id'=>$tableId, 'status'=>'due'])->get();
        $barOrderList->map(function ($order){
            $order->BarItems;
            $BarNameId = BarItems::find($order->bar_items_id)->bar_name_id;
            $BarName = BarName::find($BarNameId)->bar_name;
            $order['bar_name'] =$BarName;
        });
        

        // toBase() is used to restrict the remove of multiple object having same id during merge
        $firstMergeOrderList = $foodOrderList->toBase()->merge($coffeeOrderList);
        $allOrderList = $firstMergeOrderList->toBase()->merge($barOrderList);
        
        return $allOrderList;


        // if ($foodOrder->isNotEmpty()) {
        //     return $this->jsonResponse(true, 'List of Food Order made by table.', $foodOrder);
        // } else {
        //     return $this->jsonResponse(false, 'Currently, there is no any food order.', $foodOrder);
        // }
    }

    public function show(RoomTransaction $roomTransaction)
    {
        return $this->jsonResponse(true, 'Data of an individual room transaction.', $roomTransaction);
    }

    public function updateRoomTransaction(Request $request)
    {
        try{
            DB::beginTransaction();

            $roomTransaction = $request->all();

            // Subtracting todayDate from checkInDate to calculate number of days stayed
            $checkInDate = new \DateTime($roomTransaction['check_in_date']);
            $checkOutDate = new \DateTime($roomTransaction['check_out_date']);
            $interval = $checkOutDate->diff($checkInDate);
            $days = $interval->format('%a');


            // Update room transaction
            $roomAvailability= RoomTransaction::where(['reservation_id'=> $roomTransaction['reservation_id']])->update([
                "number_of_days"=> $days,
                "total_amount"=>$days * $roomTransaction['rate']
            ]);

            // Update roomAvailability info
            $roomAvailability= RoomAvailability::where(['reservation_id'=> $roomTransaction['reservation_id']])->update([
                "check_in_date"=> Carbon::createFromFormat('Y-m-d\TH:i:s+', $roomTransaction['check_in_date']),
                "check_out_date"=> Carbon::createFromFormat('Y-m-d\TH:i:s+', $roomTransaction['check_out_date']),
            ]);

            // Update reservation checkIn/checkOut Date
            $roomAvailability= Reservation::where(['id'=> $roomTransaction['reservation_id']])->update([
                "check_in_date"=> Carbon::createFromFormat('Y-m-d\TH:i:s+', $roomTransaction['check_in_date']),
                "check_out_date"=> Carbon::createFromFormat('Y-m-d\TH:i:s+', $roomTransaction['check_out_date']),
            ]);


            DB::commit();

            // $roomTransaction->update($this->validateRequest());
            return $this->jsonResponse(true, 'Room Transaction has been updated.', $roomTransaction);

        }
        catch(\Exception $e)
        {
            DB::rollback();
        }
    }

    public function update(RoomTransaction $roomTransaction)
    {
        $roomTransaction->update($this->validateRequest());
        return $this->jsonResponse(true, 'Room Transaction has been updated.', $roomTransaction);
    }

    public function destroy(RoomTransaction $roomTransaction)
    {
        $roomTransaction->delete();
        return $this->jsonResponse(true, 'Room Transaction has been deleted successfully.');
    }

    public function validateRequest()
    {
        return request()->validate([
            'customer_id' => 'required',
            'reservation_id' => 'required',
            'invoice_id' => 'nullable',
            'number_of_days' => 'required',
            'rate' => 'required',
            'total_amount' => 'required'
        ]);
    }

    private function jsonResponse($success = false, $message = '', $data = null, $totalRoomTransaction=0)
    {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data' => $data,
            'totalCount'=>$totalRoomTransaction
        ]);
    }
}
