<?php

namespace App\Http\Controllers;
use App\Model\Customer;
use App\Model\Booking;


class CustomerController extends Controller
{
    public function index()
    {
        $customer = Customer::all();
        if ($customer->isNotEmpty()) {
            $customer->map(function ($customer) {
                $customer->bookings;
            });
        }

        if ($customer->isNotEmpty()) {
            return $this->jsonResponse(true, 'Lists of Customers.', $customer);
        } else {
            return $this->jsonResponse(false, 'Currently, there is no any Customers yet.');
        }
    }

    public function store()
    {
        $customer = Customer::create($this->validateRequest());
        return $this->jsonResponse(true, 'Customer has been created successfully.', $customer);
    }

    public function show(Customer $customer)
    {
        return $this->jsonResponse(true, 'Data of an individual Customer.', $customer);
    }

    public function update(Customer $customer)
    {
        $customer->update($this->validateRequest());
        return $this->jsonResponse(true, 'Customer has been updated.', $customer);
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return $this->jsonResponse(true, 'Customer has been deleted successfully.');
    }

    public function validateRequest()
    {
        return request()->validate([
            'first_name' => 'required|max:50',
            'middle_name' => '',
            'last_name' => 'required',
            'country' => 'required',
            'address' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'customer_type' => 'required'
        ]);
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
