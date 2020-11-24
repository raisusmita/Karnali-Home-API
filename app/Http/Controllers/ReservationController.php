<?php

namespace App\Http\Controllers;

use App\Model\Reservation;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Services\RoomAvailabilityServices;
use App\Model\RoomAvailability;
use App\Model\Booking;




class ReservationController extends Controller
{
    public function __construct(RoomAvailabilityServices $roomAvailabilityService)
    {
        $this->roomAvailabilityService= $roomAvailabilityService;
    }


    public function index()
    {
        $reservation = Reservation::all();
        if ($reservation->isNotEmpty()) {
            $reservation->map(function ($reservation) {
                // These three data may not be required when Room Availability is implemented
                $reservation->Room;
                $reservation->Room->RoomCategory;
                $reservation->Customer;
                $reservation->Booking;
                // ------------------------------------
            });
            return $this->jsonResponse(true, 'Lists of Reservation.', $reservation);
        } else {
            return $this->jsonResponse(false, 'Currently, there is no any Reservation yet.');
        }
    }

    public function getReservationList(Request $request){
        $skip =$request->skip;
        $limit=$request->limit;
        $totalReservation = Reservation::where('status','!=','cancelled')->get()->count();

        $reservation = Reservation::where('status','!=','cancelled')->skip($skip)->take($limit)->orderBy('id', 'DESC')->get();
        if ($reservation->isNotEmpty()) {
            $reservation->map(function ($reservation) {
                $reservation->Room;
                $reservation->Room->RoomCategory;
                $reservation->Customer;
                $reservation->Booking;
            });
            return $this->jsonResponse(true, 'Lists of Reservation.', $reservation, $totalReservation);
        } else {
            return $this->jsonResponse(false, 'Currently, there is no any Reservation yet.');
        }
    }

    public function store(Request $request)
    {
        $availableRoomParams = [];
        $totalRooms = [];
        // $reservation = Reservation::create($this->validateRequest());
        // $reservation = Reservation::insert($request->all());

        // Removing first index for extracting status 
        $data = $request->all();
        $status = array_shift($data);

        // Date formatting
        $all = array_map(function($reserved){
            $reserved['check_in_date'] = Carbon::createFromFormat('Y-m-d\TH:i:s+', $reserved['check_in_date']);
            $reserved['check_out_date'] = Carbon::createFromFormat('Y-m-d\TH:i:s+', $reserved['check_out_date']);
            return $reserved;
        }, $data);

        $arrayLength= count($all);
        
        try{
            DB::beginTransaction();
            // 1- get the last id of your table ($lastIdBeforeInsertion)  56 [59,60]
            $id = DB::table('reservations')->latest('id')->first();

            // Incase the reservation table is empty return 0 as last reservation id
            if( empty($id))
            {
                $lastIdBeforeInsertion =0;
            }else{
                $lastIdBeforeInsertion = $id->id;
            }
        
            // 2- insert multiple reservation
            $reservation = Reservation::insert($all);
                
            // 3- Getting the last inserted ids
            $insertedIds = Reservation::where('id' ,'>' ,$lastIdBeforeInsertion)->get('id');

            if($status['byBooking']== true){
              
                // Update the data in room availability if the room is booked
                for ($i=0; $i < $arrayLength ; $i++) { 
                    $roomAvailability= RoomAvailability::where(['booking_id'=> $all[$i]['booking_id'], 'room_id'=> $all[$i]['room_id']])->update([
                      "reservation_id" => $insertedIds[$i]['id'],
                      "check_in_date" => $all[$i]['check_in_date'],
                      "check_out_date" => $all[$i]['check_out_date'],
                      "status" => "reserved",
                      "availability"=>"1",
                    ]);

                    $booking = Booking::where(['id'=> $all[0]['booking_id']])->update([
                        "status"=>"complete"
                    ]);
                }
            }
            else{
                // For direct reservation
                // Creating params to insert rooms in roomAvailable table for "room number" reservation input 
                for ($i=0; $i < $arrayLength ; $i++) { 
                    $availableRoomParams =  array(
                    "reservation_id" => $insertedIds[$i]['id'],
                    "room_id" => $all[$i]['room_id'],
                    "check_in_date" => $all[$i]['check_in_date'],
                    "check_out_date" => $all[$i]['check_out_date'],
                    "status" => "reserved",
                    "booking_id" => null,
                    "availability"=>"1",
                    "created_at" => Carbon::now(),
                    "updated_at" => Carbon::now(),
                    );

                    array_push($totalRooms, $availableRoomParams);
                }
                // Inserting into room availability 
                $this->roomAvailabilityService->storeRoomAvailability($totalRooms);
            }
                

            DB::commit();
            return $this->jsonResponse(true, 'Reservation has been created successfully.', $all);
        }
        catch(\Exception $e)
        {
            DB::rollback();
        }
    }

    public function show(Reservation $reservation)
    {
        $reservation->Room;
        $reservation->Customer;
        return $this->jsonResponse(true, 'Data of an individual Reservation.', $reservation);
    }

    public function update(Request $request, Reservation $reservation)
    {
        try{
            DB::beginTransaction();
            // First index is params for reservationUpdate
            $reservationParams =  array(
                "id" => $request[0]['reservation_id'],
                'check_in_date' => Carbon::createFromFormat('Y-m-d\TH:i:s+', $request[0]['check_in_date']),
                'check_out_date' =>Carbon::createFromFormat('Y-m-d\TH:i:s+', $request[0]['check_out_date']),
                'room_id' => $request[0]['room_id']
            );

            // Formatting dates
            $paramsRoomAvailable = array_map(function($reserved){
                $reserved['check_in_date'] = Carbon::createFromFormat('Y-m-d\TH:i:s+', $reserved['check_in_date']);
                $reserved['check_out_date'] = Carbon::createFromFormat('Y-m-d\TH:i:s+', $reserved['check_out_date']);
                return $reserved;
            }, $request->all());

            // Removing first index which is params for reservation update
            array_shift($paramsRoomAvailable);

            // Update reservation info
            $reservation= Reservation::where(['id'=> $reservationParams['id']])->update([
                "check_in_date" => $reservationParams['check_in_date'],
                "check_out_date" => $reservationParams['check_out_date'],
                "room_id" => $reservationParams['room_id'],
            ]);

            // Update roomAvailability info
            $roomAvailability= RoomAvailability::where(['reservation_id'=> $paramsRoomAvailable[0]['reservation_id']])->update([
                "check_in_date" => $paramsRoomAvailable[0]['check_in_date'],
                "check_out_date" => $paramsRoomAvailable[0]['check_out_date'],
                "room_id" => $paramsRoomAvailable[0]['room_id'],

            ]);

            DB::commit();
            return $this->jsonResponse(true, 'Reservation has been updated.', $reservation);

        }
        catch(\Exception $e)
        {
            DB::rollback();
        }
    }

    public function destroy(Reservation $reservation)
    {
        $reservation->delete();
        return $this->jsonResponse(true, 'Reservation has been deleted successfully.');
    }

    private function validateRequest()
    {
        foreach(request() as $data){
            return $data->validate([
                'room_id' => 'required',
                'customer_id' => 'required',
                'booking_id' => 'nullable',
                'check_in_date' => 'required',
                'check_out_date' => 'required',
            ]);
        }
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
}
