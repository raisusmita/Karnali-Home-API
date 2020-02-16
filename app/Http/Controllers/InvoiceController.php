<?php

namespace App\Http\Controllers;

use App\Model\Invoice;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InvoiceController extends Controller
{

    public function index()
    {
        $invoice = Invoice::all();
        if ($invoice->isNotEmpty()) {
            return response()->json([
                'success' => true,
                'message' => 'Lists of Invoice.',
                'data' => $invoice
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Currently, there is no any Invoice yet.',
            ]);
        }
    }

    public function store()
    {
        $invoice = Invoice::create($this->validateRequest());
        return response()->json([
            'success' => true,
            'message' => 'Invoice has been created successfully.',
            'data' => $invoice
        ]);
    }

    public function show(Invoice $invoice)
    {
        return response()->json([
            'success' => true,
            'message' => 'Data of an individual Invoice',
            'data' => $invoice
        ]);
    }

    public function update(Invoice $invoice)
    {
        $invoice->update($this->validateRequest());
        return response()->json([
            'success' => true,
            'message' => 'Invoice has been updated',
            'data' => $invoice
        ]);
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return response()->json([
            'success' => true,
            'message' => 'Invoice has been deleted successfully.'
        ]);
    }

    public function validateRequest()
    {
        return request()->validate([
            'invoice_number' => 'required | unique:invoices',
            'vat' => 'required',
            'discount' => 'required',
            'invoice_date' => 'required'
        ]);
    }
}
