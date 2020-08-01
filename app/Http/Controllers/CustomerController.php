<?php

namespace App\Http\Controllers;

use App\Model\Customer;
use Intervention\Image\Facades\Image;

class CustomerController extends Controller
{
    public function index()
    {
        $customer = Customer::all();
        if ($customer->isNotEmpty()) {
            $customer->map(function ($customer) {
                $customer->identity_image_first = $customer->identity_image_first ? asset('storage/' . $customer->identity_image_first) : "";
                $customer->identity_image_second = $customer->identity_image_second ? asset('storage/' . $customer->identity_image_second) : "";
            });
            return $this->jsonResponse(true, 'Lists of Customers.', $customer);
        } else {
            return $this->jsonResponse(false, 'Currently, there is no any Customers yet.');
        }
    }

    public function store()
    {
        $customer = Customer::create($this->validateRequest());
        $this->storeImage($customer);
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

    public function editCustomer()
    {
        $customer = Customer::find(request()->id);
        $customer->update($this->validateRequest());
        $this->storeImage($customer);
        return $this->jsonResponse(true, 'Customer has been updated.', $customer);
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return $this->jsonResponse(true, 'Customer has been deleted successfully.');
    }

    private function storeImage($identityImage)
    {
        if (request()->has('identity_image_first')) {
            $identityImage->update([
                'identity_image_first' => request()->identity_image_first->store('images/identity', 'public'),
                'identity_image_second' => request()->identity_image_second->store('images/identity', 'public'),
            ]);
            $imgFirst = Image::make(public_path('storage/identity' . $identityImage->identity_image_first))->fit(386, 235);
            $imgSecond = Image::make(public_path('storage/identity' . $identityImage->identity_image_second))->fit(386, 235);
            $imgFirst->save();
            $imgSecond->save();
        }
    }

    public function validateRequest()
    {
        return request()->validate([
            'first_name' => 'required|max:50',
            'middle_name' => '',
            'last_name' => 'required',
            'country' => 'required',
            'address' => 'required',
            'email' => 'sometimes',
            'phone' => 'required',
            'date_of_birth' => 'required',
            'profession' => 'required',
            'identity_type' => 'required',
            'identity_number' => 'required',
            'identity_image_first' => 'file|image|mimes:jpeg,png,jpg,gif|nullable|sometimes',
            'identity_image_second' => 'file|image|mimes:jpeg,png,jpg,gif|nullable|sometimes'
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
