<?php

namespace App\Http\Controllers;

use App\Model\BookingCustomer;
use Illuminate\Http\Request;

class BookingCustomerController extends Controller
{
    public function index()
    {
        // Show all booking customer detail
        $bookingCustomer = ['booking_customer' => BookingCustomer::all()];
        return $bookingCustomer;
    }

    public function store(Request $request)
    {
        // Store booking customer
        $bookingCustomer = BookingCustomer::create($this->validateRequest());
        return $bookingCustomer;
    }

    public function show(BookingCustomer $bookingCustomer)
    {
        // show individual booking customer
        return $bookingCustomer;
    }

    public function update(Request $request, BookingCustomer $bookingCustomer)
    {
        //Update booking customer
        $bookingCustomer->update($this->validateRequest());
        return $bookingCustomer;

    }

    public function destroy(BookingCustomer $bookingCustomer)
    {
        //Delete Booking Customer
        $bookingCustomer->delete();
        return 'Booking Customer Deleted Successfully';

    }

    // Form validation function
    public function validateRequest()
    {
        return request()->validate([
            'first_name' => 'required|max:50',
            'middle_name' => '',
            'last_name' => 'required',
            'email'=> 'required|email',
            'phone'=> 'required',
        ]);
    }
}
