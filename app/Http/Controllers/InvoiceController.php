<?php

namespace App\Http\Controllers;

use App\Model\Invoice;
use App\Model\Charge;
use App\Model\RoomTransaction;
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

    public function store(Request $request)
    {
        $transactions = $request->all();
        $charges = Charge::all();

        $tax = 0;
        $discount = 0; 
        $serviceCharge = 0;
        $vAT = 0;
        $total_amount = 0;
        $transactionNumber = '';
        $transactionDetail = [];
        $initialTransactionDetail=[];
        $finalTransactionDetail=[];

        // Extracting charges value from charge table
        foreach($charges as $charge){
           if($charge['charges'] == 'Tax'){
               $tax = $charge['value'] ;
           }
           else if($charge['charges'] == 'Discount'){
            $discount = $charge['value'] ;
           }
           else if($charge['charges'] == 'Service charge'){
            $serviceCharge = $charge['value'] ;
           }
           else if($charge['charges'] == 'VAT'){
            $vAT = $charge['value'] ;
           }
        }
      

        // Calculating total amount from all transactions 
        // Also storing each transactions number to generate invoiceNumber later
        foreach($transactions as $transaction){


            $total_amount = (double)$total_amount + (double)$transaction['amount'];
            $transactionNumber = $transactionNumber . $transaction['transaction_id'];

            // Retrieve all the necessary data
            $transactionDetail = RoomTransaction::where(['id'=>$transaction['transaction_id']])->get();
            $transactionDetail[0]->reservation;
            $transactionDetail[0]['reservation']->room;
            $transactionDetail[0]['reservation']['room']->roomCategory;
            $roomDetail = $transactionDetail[0]['reservation']['room'];

            // Re-arrange the data and store in an array
            $initialTransactionDetail =  array(
                "roomNumber" => $transactionDetail[0]['reservation']['room']->room_number,
                "roomDetail" => $transactionDetail[0]['reservation']['room'],
                "checkInDate" =>$transactionDetail[0]['reservation']->check_in_date,
                "checkOutDate" => $transactionDetail[0]['reservation']->check_out_date,
                "numberOfDays" => $transactionDetail[0]->number_of_days,
                "rate"=> $transactionDetail[0]->rate,
                "amount"=> $transactionDetail[0]->total_amount,
                "subtotal"=>$total_amount

            );

            array_push($finalTransactionDetail, $initialTransactionDetail);

        }

        // Actual calculation for each charges
        $appliedVAT = (double)($vAT/100)* $total_amount;
        $appliedDiscount = (double)($discount/100)* $total_amount;
        $appliedTax = (double)($tax/100)* $total_amount;
        $appliedServiceCharge = (double)($serviceCharge/100)* $total_amount;

         // Params for Invoice
         $invoiceParams =  array(
            "invoice_number" => 'INV00'. $transactionNumber,
            "service_charge" => $serviceCharge,
            "tax" =>$tax,
            "vat" => $vAT,
            "discount" => $discount,
            "sub_total"=> $total_amount,
            "grand_total"=> $total_amount + $appliedServiceCharge + $appliedTax + $appliedVAT - $appliedDiscount,
        );

        
        // insert into invoice table
        $invoice = Invoice::create($invoiceParams);
        
        // Store invoice related data in previously made final array
        $initialTransactionDetail= array("invoice"=>$invoice);
        array_push($finalTransactionDetail, $initialTransactionDetail);
        
        $invoiceId = $invoice['id'];
        $invoiceNumber = $invoice['invoice_number'];

         // Update room transaction 
         foreach($transactions as $transaction){

            $roomAvailability= RoomTransaction::where(['id'=> $transaction['transaction_id']])->update([
                "invoice_id" => $invoiceId
            ]);
        }
        
      
        return $this->jsonResponse(true, 'Invoice has been created successfully.', $finalTransactionDetail);
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
