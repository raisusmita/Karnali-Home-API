<?php

namespace App\Http\Controllers;
use App\Model\Customer;
use App\Model\Booking;
use Illuminate\Http\Request;

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
                // $activeBooking= Booking::where(['status'=>'active', 'customer_id'=>$customer->id])->exists();

                // if($activeBooking){
                $customer->booking;
                // }

            });
            return $this->jsonResponse(true, 'Lists of Customers.', $customer);
        
        } else {
            return $this->jsonResponse(false, 'Currently, there is no any Customers yet.');
        }
    }

    
    public function getCustomerList(Request $request){

        $skip =$request->skip;
        $limit=$request->limit;
        $totalCustomer = Customer::get()->count();

        // using where clause just to get data in required format
        $customer = Customer::where('id','!=', 0)->skip($skip)->take($limit)->orderBy('id', 'DESC')->get();
        if ($customer->isNotEmpty()) {
            $customer->map(function ($customer) {
                $customer->identity_image_first = $customer->identity_image_first ? asset('storage/' . $customer->identity_image_first) : "";
                $customer->identity_image_second = $customer->identity_image_second ? asset('storage/' . $customer->identity_image_second) : "";
                $customer->booking;
            });
            return $this->jsonResponse(true, 'Lists of Customers.', $customer, $totalCustomer);
        
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
            $imgFirst = Image::make(public_path('storage/' . $identityImage->identity_image_first))->resize(386, 235);
            $imgSecond = Image::make(public_path('storage/' . $identityImage->identity_image_second))->resize(386, 235);
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
            'profession' => '',
            'identity_type' => 'required',
            'identity_number' => '',
            'identity_image_first' => 'file|image|mimes:jpeg,png,jpg,gif|nullable|sometimes',
            'identity_image_second' => 'file|image|mimes:jpeg,png,jpg,gif|nullable|sometimes'
        ]);
    }

    private function jsonResponse($success = false, $message = '', $data = null, $totalCustomer=0)
    {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data' => $data,
            'totalCount'=>$totalCustomer
        ]);
    }
}
