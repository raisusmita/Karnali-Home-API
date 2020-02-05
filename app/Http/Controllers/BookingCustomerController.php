<?php

namespace App\Http\Controllers;

use App\Model\BookingCustomer;
use Illuminate\Http\Request;

class BookingCustomerController extends Controller
{
    public function index()
    {
        $bookingCustomer = ['booking_customer' => BookingCustomer::all()];
        return $bookingCustomer;
    }
    public function store(Request $request)
    {
        $bookingCustomer = $request->validate([
            'first_name' => 'required|max:50',
            'middle_name' => '',
            'last_name' => 'required',
            'email'=> 'required|email',
            'phone'=> 'required',
        ]);

        $bookingCustomer = new BookingCustomer;
        $bookingCustomer->first_name = $request->first_name;
        $bookingCustomer->middle_name = $request->middle_name;
        $bookingCustomer->last_name = $request->last_name;
        $bookingCustomer->email = $request->email;
        $bookingCustomer->phone = $request->phone;

        // $bookingCustomerData = BookingCustomer::create($this->bookingCustomer);
        $bookingCustomer->save();

        return $bookingCustomer;
    }
    public function show(BookingCustomer $bookingCustomer)
    {
        return $bookingCustomer;
    }

    public function update(Request $request, BookingCustomer $bookingCustomer)
    {
        //

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\BookingCustomer  $bookingCustomer
     * @return \Illuminate\Http\Response
     */
    public function destroy(BookingCustomer $bookingCustomer)
    {
        //
        $bookingCustomer->delete();
        return 'Booking Customer Deleted Successfully';

    }
}
