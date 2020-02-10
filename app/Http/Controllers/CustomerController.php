<?php

namespace App\Http\Controllers;

use App\Model\Customer;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomerController extends Controller
{
    public function index()
    {
        $customer = Customer::all();
        if ($customer->isNotEmpty()) {
            return response([
                'success' => true,
                'message' => 'Lists of Customers.',
                'data' => $customer
            ], Response::HTTP_CREATED);
        } else {
            return response([
                'success' => false,
                'message' => 'Currently, there is no any Customers yet.',
            ], Response::HTTP_CREATED);
        }
    }

    public function store()
    {
        $customer = Customer::create($this->validateRequest());
        return response([
            'success' => true,
            'message' => 'Customer has been created successfully.',
            'data' => $customer
        ], Response::HTTP_CREATED);
    }

    public function show(Customer $customer)
    {
        return response([
            'success' => true,
            'message' => 'Data of an individual Customer',
            'data' => $customer
        ], Response::HTTP_CREATED);
    }

    public function update(Customer $customer)
    {
        $customer->update($this->validateRequest());
        return response([
            'success' => true,
            'message' => 'Customer has been updated',
            'data' => $customer
        ], Response::HTTP_CREATED);
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return response([
            'success' => true,
            'message' => 'Customer has been deleted successfully.'
        ], Response::HTTP_NO_CONTENT);
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
