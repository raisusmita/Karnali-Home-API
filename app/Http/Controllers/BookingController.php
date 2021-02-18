<?php

namespace App\Http\Controllers;

use App\Mail\BookingMail;
use App\Model\Booking;
use App\Model\Customer;
use App\Model\RoomAvailability;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Services\RoomAvailabilityServices;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Validator;



class BookingController extends Controller
{
    protected $CustomerController;
    public function __construct(RoomAvailabilityServices $roomAvailabilityService, CustomerController $CustomerController )
    {
        $this->roomAvailabilityService= $roomAvailabilityService;
        $this->CustomerController = $CustomerController;
    }

    public function index()
    {
        
        $booking = Booking::all();
        
        if ($booking->isNotEmpty()) {
            $booking->map(function ($booking) {
                $booking->Customer;
                $booking->BookedRoom;
            });
            
            return $this->jsonResponse(true, 'Lists of Bookings.', $booking);
        } else {
            return $this->jsonResponse(false, 'Currently, there is no any Bookings yet.');
        }
    }

    public function getBookingList(Request $request){

        $skip =$request->skip;
        $limit=$request->limit;
        $totalBooking = Booking::where('status','!=','cancelled')->get()->count();

        $booking = Booking::where('status','!=','cancelled')->skip($skip)->take($limit)->orderBy('id', 'DESC')->get();
        if ($booking->isNotEmpty()) {
            $booking->map(function ($booking) {
                $booking->Customer;
                $booking->RoomCategory;
                $booking->Rooms;
            });

                        
            return $this->jsonResponse(false, 'Currently, there is no any BookedRooms yet.', $booking, $totalBooking);
        }
    }

    public function getActiveBooking()
    {
        $booking = Booking::where(['status'=>'active'])->get();
        if ($booking->isNotEmpty()) {
            
            return $this->jsonResponse(true, 'Lists of Bookings.', $booking);
        } else {
            return $this->jsonResponse(false, 'Currently, there is no any Bookings yet.');
        }
    }

    public function store(Request $request)
    {
        try{
            DB::beginTransaction();
            $message = '';
            $room = [];
            $availableRoomParams = [];
            $totalRooms = [];
            // Create room booking
            $booking = Booking::create($this->validateRequest($request));
            $booking = Booking::all()->last();
            // Get available room 
            $availableRoom = $this->roomAvailabilityService->getAvailableRoom();
            // Parsing the string to array and decode back to array
            $encoded = json_encode( $availableRoom, true);
            $decoded = json_decode( $encoded, true);
            $RoomData = $decoded['original'];
            if (is_array($RoomData) || is_object($RoomData)){
                // Store rooms that belongs to booked room category
                foreach ($RoomData['data'] as $data)
                {
                    if($data['room_category_id'] == $booking->room_category_id){
                        array_push($room, $data);
                    }
                }
                if(count($room)>0){
                    // Creating params to insert rooms in roomAvailable table for "number of rooms" booking input 
                    for ($i=0; $i < $booking->number_of_rooms ; $i++) { 
                        $availableRoomParams =  array(
                            "reservation_id" => null,
                            "room_id" => $room[$i]['id'],
                            "check_in_date" => $booking->check_in_date,
                            "check_out_date" => $booking->check_out_date,
                            "status" => "booked",
                            "availability"=> "1",
                            "booking_id" => $booking->id,
                            "availability"=>"1",
                            "created_at" => $booking->created_at,
                            "updated_at" => $booking->updated_at,
                        );
                        array_push($totalRooms, $availableRoomParams);
                    } 
                    // Inserting into room availability 
                    $roomAvailavleData= $this->roomAvailabilityService->storeRoomAvailability($totalRooms);
                }
                $userEmail = $booking->customer->email;
                if ($booking && $userEmail && $roomAvailavleData) {
                    Mail::to($userEmail)->send(new BookingMail($booking->check_in_date, $booking->check_out_date));
                    $message = 'Booking has been created successfully.';
                } else if ($booking) {
                    $message = 'Booking has been created successfully. But email failed';
                } else {
                    $message = 'Booking failed';
                }
                DB::commit();
                return $this->jsonResponse(true, $message, $booking);
            }

            else{
                $message = 'No room is available for booking';
                DB::commit();
                return $this->jsonResponse(true, $message, $RoomData);
            }

        }
        catch(\Exception $e)
        {
            $message = $e->getMessage();
            DB::rollback();
            return $this->jsonResponse(false, $message);
        }
    }

    public function storeMultipleBooking(Request $request)
    {
        try{
            DB::beginTransaction();
            $allDetail = $request->all();
            $customerDetail = $allDetail['customer'];
            $customer = Customer::create($customerDetail);
            $customerId =$customer->id;
            $bookingDetail = $allDetail['booking'];
            $bookingDates = $allDetail['bookingDates'];
            $totalBookingBefore = Booking::all()->count();
            $count =0;
            foreach( $bookingDetail as $key => $value )
            {
                    if($value['number_of_rooms']){
                        $count = $count +1;
                        $bookingParams =  array(
                            "customer_id" => $customerId,
                            "room_category_id" => $key,
                            "number_of_rooms" =>$value['number_of_rooms'],
                            "number_of_adult" => $value['number_of_adults'],
                            "number_of_child" => $value['number_of_children'],
                            "check_in_date"=>  date('Y-m-d H:i:s', strtotime($bookingDates['checkInDate'])),
                            "check_out_date"=>   date('Y-m-d H:i:s', strtotime($bookingDates['checkOutDate'])),
                            "status"=> "active"
                        );
                        $request = new Request();
                        $request->replace($bookingParams);
                        $this->store( $request);
                    }
            }
            $totalBookingAfter = Booking::all()->count();
            $actualNewBooking = $totalBookingBefore + $count;

            if($actualNewBooking == $totalBookingAfter){
                $message = 'Booking has been created successfully.';
                DB::commit();
                return $this->jsonResponse(true, $message);
            }else{
                $message = 'Booking failed';
                return $this->jsonResponse(false, $message);
            }
        }
        catch(\Exception $e)
        {
            $message = $e->getMessage();
            DB::rollback();
            return $this->jsonResponse(false, $message);
        }
    }

  
    public function show(Booking $booking)
    {
        return $this->jsonResponse(true, 'Data of an individual Booking.', $booking);
    }

    public function update(Request $request, Booking $booking)
    {
        try{
            DB::beginTransaction();

            $bookingParams =  array(
                "id" => $request[0]['id'],
                'customer_id' => $request[0]['customer_id'],
                'room_category_id' => $request[0]['room_category_id'],
                'number_of_rooms' => $request[0]['number_of_rooms'],
                'number_of_adult' => $request[0]['number_of_adult'],
                'number_of_child' => $request[0]['number_of_child'],
                'check_in_date' => $request[0]['check_in_date'],
                'check_out_date' => $request[0]['check_out_date']
            );

            // Formatting dates
            $paramsRoomAvailable = array_map(function($reserved){
                $reserved['check_in_date'] = Carbon::createFromFormat('Y-m-d\TH:i:s+', $reserved['check_in_date']);
                $reserved['check_out_date'] = Carbon::createFromFormat('Y-m-d\TH:i:s+', $reserved['check_out_date']);
                return $reserved;
            }, $request->all());

            // Removing first index which is params for booking insertion
            array_shift($paramsRoomAvailable);

            // Update booking info
            $booking->update($bookingParams);

            // Delete previous Room Available
            $removeRoomAvailable = RoomAvailability::where(['booking_id'=>$request[0]['id']])->delete();

            // Inserting into room availability 
            $roomAvailable= $this->roomAvailabilityService->storeRoomAvailability($paramsRoomAvailable);

            DB::commit();
            return $this->jsonResponse(true, 'Booking has been updated.', $roomAvailable);

        }
        catch(\Exception $e)
        {
            $message = $e->getMessage();
            DB::rollback();
            return $this->jsonResponse(false, $message);
        }
    }

    public function bookingCancelled(Request $request){

        try{
            DB::beginTransaction();
            $booking = $request->all();

            RoomAvailability::where(['booking_id'=>$booking['bookingId']])->update([
                'availability'=> '0',
                'status'=>'cancelled'
                ]);

            Booking::where(['id'=>$booking['bookingId']])->update([
                'status'=>'cancelled'
            ]);
            DB::commit();
            return $this->jsonResponse(true, 'Booking has been cancelled successfully.', $booking);
        }
        catch(\Exception $e)
        {
            $message = $e->getMessage();
            DB::rollback();
            return $this->jsonResponse(false, $message);
        }
    }

    public function destroy(Booking $booking)
    {
        try{
            DB::beginTransaction();

            // Deleting from room availabilities
            RoomAvailability::where(['booking_id'=>$booking->id])->delete();

            // Deleting from booking
            $booking->delete();

            DB::commit();
            return $this->jsonResponse(true, 'Booking has been deleted successfully.');
        }
        catch(\Exception $e)
        {
            $message = $e->getMessage();
            DB::rollback();
            return $this->jsonResponse(false, $message);
        }
    }
    
    public function getBookedRoom()
    {
        $booking = Booking::where('status','!=','cancelled')->get();
        if ($booking->isNotEmpty()) {
            $booking->map(function ($booking) {
                $booking->Customer;
                $booking->RoomCategory;
                $booking->Rooms;
            });
            
            return $this->jsonResponse(false, 'Currently, there is no any BookedRooms yet.', $booking);
        }
    }
    
        public function validateRequest($request)
        {
            
            $validateData = $request->validate([
                'customer_id' => 'required',
                'room_category_id' => 'required',
                'number_of_rooms' => 'required',
                'number_of_adult' => '',
                'number_of_child' => '',
                'check_in_date' => 'required',
                'check_out_date' => 'required',
            ]);

            return $validateData;
        }
    
    private function jsonResponse($success = false, $message = '', $data = null, $totalCount=0)
    {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data' => $data,
            'totalCount'=>$totalCount
        ]);
    }

    //Store for booked_room table
    // public function storeBookedRoom()
    // {
    //     $bookedRoom = BookedRoom::create($this->validateBookedRoomRequest());
    //     return response()->json([
    //         'success' => true,
    //         'message' => 'BookedRoom has been created successfully.',
    //         'data' => $bookedRoom
    //     ]);
    // }

    // public function showBookedRoom(BookedRoom $bookedRoom)
    // {
    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Data of an individual bookedRoom',
    //         'data' => $bookedRoom
    //     ]);
    // }

    // public function validateBookedRoomRequest()
    // {
    //     return request()->validate([
    //         'room_category_id' => 'required',
    //         'booking_id' => 'required',
    //         'number_of_rooms' => 'required'
    //     ]);
    // }

}
