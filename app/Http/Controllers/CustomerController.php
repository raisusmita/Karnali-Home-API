<?php

namespace App\Http\Controllers;

use App\Model\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        // Show all  customer detail
        $Customer = ['customer' => Customer::all()];
        return $Customer;
    }

    public function store(Request $request)
    {
        // Store  customer
        $customer = Customer::create($this->validateRequest());
        return $customer;
    }

    public function show(Customer $customer)
    {
        // show individual  customer
        return $customer;
    }

    public function update(Customer $customer)
    {
        //Update  customer
        $customer->update($this->validateRequest());
        return $customer;

    }

    public function destroy(Customer $customer)
    {
        //Delete  Customer
        $customer->delete();
        return ' Customer Deleted Successfully';

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
            'customer_type' => 'required'
        ]);
    }
}
