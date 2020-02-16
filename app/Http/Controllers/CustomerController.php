<?php

namespace App\Http\Controllers;

use App\Model\Customer;

class CustomerController extends Controller
{
    public function index()
    {
        $customer = Customer::all();
        if ($customer->isNotEmpty()) {
            return response()->json([
                'success' => true,
                'message' => 'Lists of Customers.',
                'data' => $customer
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Currently, there is no any Customers yet.',
            ]);
        }
    }

    public function store()
    {
        $customer = Customer::create($this->validateRequest());

        return response()->json([
            'success' => true,
            'message' => 'Customer has been created successfully.',
            'data' => $customer
        ]);
    }

    public function show(Customer $customer)
    {
        return response()->json([
            'success' => true,
            'message' => 'Data of an individual Customer',
            'data' => $customer
        ]);
    }

    public function update(Customer $customer)
    {
        $customer->update($this->validateRequest());
        return response()->json([
            'success' => true,
            'message' => 'Customer has been updated',
            'data' => $customer
        ]);
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return response()->json([
            'success' => true,
            'message' => 'Customer has been deleted successfully.'
        ]);
    }

    public function validateRequest()
    {
        return request()->validate([
            'first_name' => 'required|max:50',
            'middle_name' => '',
            'last_name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'customer_type' => 'required'
        ]);
    }
}
