<?php

namespace App\Http\Controllers;

use App\Model\Customer;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

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
        $Customer = Customer::create($this->validateRequest());
        return $Customer;
    }

    public function show(Customer $Customer)
    {
        // show individual  customer
        return $Customer;
    }

    public function update(Request $request, Customer $Customer)
    {
        //Update  customer
        $Customer->update($this->validateRequest());
        return $Customer;

    }

    public function destroy(Customer $Customer)
    {
        //Delete  Customer
        $Customer->delete();
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
