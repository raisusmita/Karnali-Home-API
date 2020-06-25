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
            return $this->jsonResponse(true, 'Lists of Invoice.', $invoice);
        } else {
            return $this->jsonResponse(false, 'Currently, there is no any Invoice yet.');
        }
    }

    public function store()
    {
        $invoice = Invoice::create($this->validateRequest());
        return $this->jsonResponse(true, 'Invoice has been created successfully.', $invoice);
    }

    public function show(Invoice $invoice)
    {
        return $this->jsonResponse(true, 'Data of an individual Invoice.', $invoice);
    }

    public function update(Invoice $invoice)
    {
        $invoice->update($this->validateRequest());
        return $this->jsonResponse(true, 'Invoice has been updated.', $invoice);
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return $this->jsonResponse(true, 'Invoice has been deleted successfully.');
    }

    private function validateRequest()
    {
        return request()->validate([
            'invoice_number' => 'required | unique:invoices',
            'vat' => 'required',
            'discount' => 'sometimes',
            'invoice_date' => 'required'
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
