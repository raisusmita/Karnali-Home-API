<?php

namespace App\Http\Controllers;

use App\Model\Reservation;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Services\RoomAvailabilityServices;



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

    public function store(Request $request)
    {
        $availableRoomParams = [];
        $totalRooms = [];
        // $reservation = Reservation::create($this->validateRequest());
        // $reservation = Reservation::insert($request->all());
        $all = array_map(function($reserved){
            $reserved['check_in_date'] = Carbon::createFromFormat('Y-m-d\TH:i:s+', $reserved['check_in_date']);
            $reserved['check_out_date'] = Carbon::createFromFormat('Y-m-d\TH:i:s+', $reserved['check_out_date']);
            return $reserved;
        }, $request->all());

        $arrayLength= count($all);



        
        try{
            DB::beginTransaction();
            // 1- get the last id of your table ($lastIdBeforeInsertion)  56 [59,60]
            $id = DB::table('reservations')->latest('id')->first();
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
          
    
            // Creating params to insert rooms in roomAvailable table for "room number" reservation input 
            for ($i=0; $i < $arrayLength ; $i++) { 
                $availableRoomParams =  array(
                   "reservation_id" => $insertedIds[$i]['id'],
                   "room_id" => $all[$i]['room_id'],
                   "check_in_date" => $all[$i]['check_in_date'],
                   "check_out_date" => $all[$i]['check_out_date'],
                   "status" => "reserved",
                   "booking_id" => null,
                   "created_at" => Carbon::now(),
                   "updated_at" => Carbon::now(),
                 );
    
                 array_push($totalRooms, $availableRoomParams);
    
              }

            // Inserting into room availability 
            $this->roomAvailabilityService->storeRoomAvailability($totalRooms);
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

    public function update(Reservation $reservation)
    {
        $reservation->update($this->validateRequest());
        return $this->jsonResponse(true, 'Reservation has been updated.', $reservation);
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

    private function jsonResponse($success = false, $message = '', $data = null)
    {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data' => $data
        ]);
    }
}
