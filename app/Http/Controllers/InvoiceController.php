<?php

namespace App\Http\Controllers;

use App\Model\Invoice;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $invoice = Invoice::all();
        if ($invoice->isNotEmpty()) {
            return response([
                'success' => true,
                'message' => 'Lists of Invoice.',
                'data' => $invoice
            ], Response::HTTP_CREATED);
        } else {
            return response([
                'success' => false,
                'message' => 'Currently, there is no any Invoice yet.',
            ], Response::HTTP_CREATED);
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $invoice = Invoice::create($this->validateRequest());
        return response([
            'success' => true,
            'message' => 'Invoice has been created successfully.',
            'data' => $invoice
        ], Response::HTTP_CREATED);
    }

    public function show(Invoice $invoice)
    {
        return response([
            'success' => true,
            'message' => 'Data of an individual Invoice',
            'data' => $invoice
        ], Response::HTTP_CREATED);
    }

    public function update(Invoice $invoice)
    {
        $invoice->update($this->validateRequest());
        return response([
            'success' => true,
            'message' => 'Invoice has been updated',
            'data' => $invoice
        ], Response::HTTP_CREATED);
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return response([
            'success' => true,
            'message' => 'Invoice has been deleted successfully.'
        ], Response::HTTP_NO_CONTENT);
    }

    public function validateRequest()
    {
        return request()->validate([
            'invoice_number' => 'required | unique:invoices',
            'vat' =>'required',
            'discount' =>'required',
            'invoice_date' => 'required'
        ]);

    }
}
