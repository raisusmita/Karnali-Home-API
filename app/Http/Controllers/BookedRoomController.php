<?php

namespace App\Http\Controllers;

use App\Model\BookedRoom;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BookedRoomController extends Controller
{

    //     public function index()
    //     {
    //         $bookedRoom = BookedRoom::all();
    //         if ($bookedRoom->isNotEmpty()) {
    //             return response()->json([
    //                 'success' => true,
    //                 'message' => 'Lists of Booked Rooms.',
    //                 'data' => $bookedRoom
    //             ]);
    //         } else {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Currently, there is no any Booked Room yet.',
    //             ]);
    //         }
    //     }

    //     public function store()
    //     {
    //         $bookedRoom = BookedRoom::create($this->validateRequest());
    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Booked Room has been created successfully.',
    //             'data' => $bookedRoom
    //         ]);
    //     }

    //     public function show(BookedRoom $bookedRoom)
    //     {
    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Data of an individual Booked Room',
    //             'data' => $bookedRoom
    //         ]);
    //     }

    //     public function update(BookedRoom $bookedRoom)
    //     {
    //         $bookedRoom->update($this->validateRequest());
    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Booked Room has been updated',
    //             'data' => $bookedRoom
    //         ]);
    //     }

    //     public function destroy(BookedRoom $bookedRoom)
    //     {
    //         $bookedRoom->delete();
    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Booked Room has been deleted successfully.'
    //         ]);
    //     }

    //     public function validateRequest()
    //     {
    //         return request()->validate([
    //             'booking_id' => 'required',
    //             'room_category_id' => 'required',
    //             'number_of_rooms' => 'required'
    //         ]);
    //     }
}
