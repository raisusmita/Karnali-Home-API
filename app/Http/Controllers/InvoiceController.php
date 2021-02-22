<?php

namespace App\Http\Controllers;

use App\Model\Invoice;
use App\Model\Charge;
use App\Model\Reservation;
use App\Model\RoomTransaction;
use App\Model\FoodOrderList;
use App\Model\BarOrderList;
use App\Model\CoffeeOrderList;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;

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


    public function getInvoiceList(Request $request)
    {

        $skip = $request->skip;
        $limit = $request->limit;
        $totalInvoice = Invoice::get()->count();

        // using where clause just to get data in required format
        $invoice = Invoice::where('id', '!=', 0)->skip($skip)->take($limit)->orderBy('id', 'DESC')->get();
        if ($invoice->isNotEmpty()) {

            return $this->jsonResponse(true, 'List of Invoices.', $invoice, $totalInvoice);
        } else {
            return $this->jsonResponse(false, 'Currently, there is no any invoice.', $invoice, $totalInvoice);
        }
    }

    public function getFoodTotalByRoom($checkInDate, $checkOutDate, $roomId)
    {
        $foodTotal = 0;
        // Get food order list
        $foodOrderLists = FoodOrderList::where('room_id', $roomId)->whereBetween('created_at', [$checkInDate, $checkOutDate])->get();

        foreach ($foodOrderLists as $foodOrderList) {
            $foodTotal = $foodTotal + ((float)$foodOrderList->price * (float)$foodOrderList->quantity);
        }
        return [$foodTotal, $foodOrderLists];
    }

    public function getBarTotalByRoom($checkInDate, $checkOutDate, $roomId)
    {
        $barTotal = 0;
        // Get bar order list
        $barOrderLists = BarOrderList::where('room_id', $roomId)->whereBetween('created_at', [$checkInDate, $checkOutDate])->get();


        foreach ($barOrderLists as $barOrderList) {
            $barTotal = $barTotal + ((float)$barOrderList->price * (float)$barOrderList->quantity);
        }
        return [$barTotal, $barOrderLists];
    }

    public function getCoffeeTotalByRoom($checkInDate, $checkOutDate, $roomId)
    {
        $coffeeTotal = 0;
        // Get coffee order list
        $coffeeOrderLists = CoffeeOrderList::where('room_id', $roomId)->whereBetween('created_at', [$checkInDate, $checkOutDate])->get();

        foreach ($coffeeOrderLists as $coffeeOrderList) {
            $coffeeTotal = $coffeeTotal + ((float)$coffeeOrderList->price * (float)$coffeeOrderList->quantity);
        }
        return [$coffeeTotal, $coffeeOrderLists];
    }

    public function getFoodTotalByTable($transactions)
    {
        $foodTotal = 0;
        foreach ($transactions as $transaction) {
            $tableNumber = $transaction['table_id'];
            $tableId = $transaction['table_id'];
            $foodOrderLists = FoodOrderList::where(['table_id' => $tableId, 'invoice_id' => null])->get();

            foreach ($foodOrderLists as $foodOrderList) {
                $foodTotal = $foodTotal + ((float)$foodOrderList->price * (float)$foodOrderList->quantity);
            }
        }
        return [$foodTotal, $foodOrderLists];
    }

    public function getBarTotalByTable($transactions)
    {
        $barTotal = 0;
        foreach ($transactions as $transaction) {
            $tableNumber = $transaction['table_id'];
            $tableId = $transaction['table_id'];
            $barOrderLists = BarOrderList::where(['table_id' => $tableId, 'invoice_id' => null])->get();

            foreach ($barOrderLists as $barOrderList) {
                $barTotal = $barTotal + ((float)$barOrderList->price * (float)$barOrderList->quantity);
            }
        }
        return [$barTotal, $barOrderLists];
    }

    public function getCoffeeTotalByTable($transactions)
    {
        $coffeeTotal = 0;
        foreach ($transactions as $transaction) {
            $tableNumber = $transaction['table_id'];
            $tableId = $transaction['table_id'];
            $coffeeOrderLists = CoffeeOrderList::where(['table_id' => $tableId, 'invoice_id' => null])->get();

            foreach ($coffeeOrderLists as $coffeeOrderList) {
                $coffeeTotal = $coffeeTotal + ((float)$coffeeOrderList->price * (float)$coffeeOrderList->quantity);
            }
        }
        return [$coffeeTotal, $coffeeOrderLists];
    }

    public function updateOrderListsByRoom($invoiceId, $foodOrderLists, $barOrderLists, $coffeeOrderLists)
    {
        // Update Food Order List
        foreach ($foodOrderLists as $foodOrderList) {
            FoodOrderList::where(['id' => $foodOrderList['id']])->update([
                "invoice_id" => $invoiceId,
                "status" => "paid",
                "order_status" => "completed"
            ]);
        }
        // Update Bar Order List
        foreach ($barOrderLists as $barOrderList) {
            BarOrderList::where(['id' => $barOrderList['id']])->update([
                "invoice_id" => $invoiceId,
                "status" => "paid",
                "order_status" => "completed"
            ]);
        }
        // Update Coffee Order List
        foreach ($coffeeOrderLists as $coffeeOrderList) {
            CoffeeOrderList::where(['id' => $coffeeOrderList['id']])->update([
                "invoice_id" => $invoiceId,
                "status" => "paid",
                "order_status" => "completed"
            ]);
        }
    }

    public function updateOrderListsByTable($transactions, $invoiceId)
    {
        foreach ($transactions as $transaction) {
            $tableNumber = $transaction['table_id'];
            $tableId = $transaction['table_id'];
            // Update Food Order List
            FoodOrderList::where(['table_id' => $tableId, 'invoice_id' => null])->update([
                "invoice_id" => $invoiceId,
                "status" => "paid",
                "order_status" => "completed"
            ]);
            // Update Bar Order List
            BarOrderList::where(['table_id' => $tableId, 'invoice_id' => null])->update([
                "invoice_id" => $invoiceId,
                "status" => "paid",
                "order_status" => "completed"
            ]);
            // Update Coffee Order List
            CoffeeOrderList::where(['table_id' => $tableId, 'invoice_id' => null])->update([
                "invoice_id" => $invoiceId,
                "status" => "paid",
                "order_status" => "completed"
            ]);
        }
    }


    public function store(Request $request)
    {
        // try{
        //     DB::beginTransaction();
        $tax = 0;
        $discount = 0;
        $serviceCharge = 0;
        $vAT = 0;
        $total_amount = 0;
        $foodTotal = 0;
        $barTotal = 0;
        $coffeeTotal = 0;
        $transactionNumber = '';
        $tableNumber = '';
        $transactionDetail = [];
        $initialTransactionDetail = [];
        $finalTransactionDetail = [];
        $transactions = $request->all();

        if (($transactions[0]['callFrom']) == 'transaction') {
            // Get Food Order for updating the invoice id in food order table 
            $roomId = $transactions[0]['room_id'];
            $reservationId = $transactions[0]['reservation_id'];
            $reservation = Reservation::where('id', $reservationId)->get();
            $checkInDate = $reservation[0]->check_in_date;
            $checkOutDate = $reservation[0]->check_out_date;

            //Get Food total amount and order lists
            $foodOrderDetail = $this->getFoodTotalByRoom($checkInDate, $checkOutDate, $roomId);
            $foodTotal = $foodOrderDetail[0];
            $foodOrderLists = $foodOrderDetail[1];

            //Get Bar total amount and order lists
            $barOrderDetail = $this->getBarTotalByRoom($checkInDate, $checkOutDate, $roomId);
            $barTotal = $barOrderDetail[0];
            $barOrderLists = $barOrderDetail[1];

            //Get Coffee total amount and order lists
            $coffeeOrderDetail = $this->getCoffeeTotalByRoom($checkInDate, $checkOutDate, $roomId);
            $coffeeTotal = $coffeeOrderDetail[0];
            $coffeeOrderLists = $coffeeOrderDetail[1];

            $charges = Charge::all();
            // Extracting charges value from charge table
            foreach ($charges as $charge) {
                if ($charge['charges'] == 'Tax') {
                    $tax = $charge['value'];
                } else if ($charge['charges'] == 'Discount') {
                    $discount = $charge['value'];
                } else if ($charge['charges'] == 'Service charge') {
                    $serviceCharge = $charge['value'];
                } else if ($charge['charges'] == 'VAT') {
                    $vAT = $charge['value'];
                }
            }
            // Calculating total amount from all transactions 
            // Also storing each transactions number to generate invoiceNumber later
            $transactionNumber = $transactionNumber . $transactions[0]['transaction_id'];
        }

        if (($transactions[0]['callFrom']) == 'transaction' || ($transactions[0]['callFrom']) == 'invoice') {
            foreach ($transactions as $transaction) {
                $total_amount = (float)$total_amount + (float)$transaction['amount'];
                // Retrieve all the necessary data
                $transactionDetail = RoomTransaction::where(['id' => $transaction['transaction_id']])->get();
                $transactionDetail[0]->reservation;
                $transactionDetail[0]['reservation']->room;
                $transactionDetail[0]['reservation']['room']->roomCategory;
                // Re-arrange the data and store in an array
                $initialTransactionDetail =  array(
                    "roomNumber" => $transactionDetail[0]['reservation']['room']->room_number,
                    "roomDetail" => $transactionDetail[0]['reservation']['room'],
                    "checkInDate" => $transactionDetail[0]['reservation']->check_in_date,
                    "checkOutDate" => $transactionDetail[0]['reservation']->check_out_date,
                    "numberOfDays" => $transactionDetail[0]->number_of_days,
                    "rate" => $transactionDetail[0]->rate,
                    "amount" => number_format($transactionDetail[0]->total_amount, 2),
                    "subtotal" => number_format($total_amount, 2)
                );
                array_push($finalTransactionDetail, $initialTransactionDetail);
            }
        }
        // invoice is only created when call from transaction and table transaction
        if (($transactions[0]['callFrom']) == 'invoice') {
            $invoice = array(
                "invoice_number" => $transaction['invoice_number'],
                "service_charge" => $transaction['service_charge'],
                "tax" => $transaction['tax'],
                "vat" => $transaction['vat'],
                "discount" => $transaction['discount'],
                "sub_total" => $transaction['sub_total'],
                "grand_total" => $transaction['grand_total'],
                "id" => $transaction['invoice_id'],
                "created_at" => $transaction['created_at']
            );
            // Store invoice related data in previously made final array
            $initialTransactionDetail = array("invoice" => $invoice);
            array_push($finalTransactionDetail, $initialTransactionDetail);
        } else {
            // Actual calculation for each charges
            $appliedVAT = (float)($vAT / 100) * $total_amount;
            $appliedDiscount = (float)($discount / 100) * $total_amount;
            $appliedTax = (float)($tax / 100) * $total_amount;
            $appliedServiceCharge = (float)($serviceCharge / 100) * $total_amount;
            if (($transactions[0]['callFrom']) == 'transaction') {
                // Params for Invoice
                $invoiceParams =  array(
                    "service_charge" => $serviceCharge,
                    "tax" => $tax,
                    "vat" => $vAT,
                    "discount" => $discount,
                    "sub_total" => (float)$total_amount + (float)$foodTotal + (float)$barTotal + (float)$coffeeTotal,
                    "grand_total" => (float)(($total_amount + (float)$foodTotal) + (float)$barTotal + (float)$coffeeTotal + $appliedServiceCharge + $appliedTax + $appliedVAT - $appliedDiscount),
                );
            } else if (($transactions[0]['callFrom']) == 'tableTransaction') {
                //Get Food total amount and order lists
                $foodOrderDetail = $this->getFoodTotalByTable($transactions);
                $foodTotal = $foodOrderDetail[0];
                $foodOrderLists = $foodOrderDetail[1];
                //Get Bar total amount and order lists
                $barOrderDetail = $this->getBarTotalByTable($transactions);
                $barTotal = $barOrderDetail[0];
                $barOrderLists = $barOrderDetail[1];
                //Get Coffee total amount and order lists
                $coffeeOrderDetail = $this->getCoffeeTotalByTable($transactions);
                $coffeeTotal = $coffeeOrderDetail[0];
                $coffeeOrderLists = $coffeeOrderDetail[1];
                // Params for Invoice
                $invoiceParams =  array(
                    "service_charge" => $serviceCharge,
                    "tax" => $tax,
                    "vat" => $vAT,
                    "discount" => $discount,
                    "sub_total" => (float)$foodTotal + (float)$barTotal + (float)$coffeeTotal,
                    "grand_total" => (float)($foodTotal + $barTotal + $coffeeTotal + $appliedServiceCharge + $appliedTax + $appliedVAT - $appliedDiscount),
                );
            }
            // insert into invoice table
            $invoice = Invoice::create($invoiceParams);
            $invoice->invoice_number='INV00' . $invoice->id;
            $invoice->save();
            // Store invoice related data in previously made final array
            $initialTransactionDetail = array("invoice" => $invoice);
            array_push($finalTransactionDetail, $initialTransactionDetail);
            $invoiceId = $invoice['id'];
            if (($transactions[0]['callFrom']) == 'transaction') {
                // Update room transaction 
                foreach ($transactions as $transaction) {
                    RoomTransaction::where(['id' => $transaction['transaction_id']])->update([
                        "invoice_id" => $invoiceId
                    ]);
                }
            }
            // Update food items 
            if (($transactions[0]['callFrom']) == 'tableTransaction') {
                $this->updateOrderListsByTable($transactions, $invoiceId);
            } else {
                $this->updateOrderListsByRoom($invoiceId, $foodOrderLists, $barOrderLists, $coffeeOrderLists);
            }
        }
        return $this->jsonResponse(true, 'Invoice has been created successfully.', $finalTransactionDetail);
        // }
        // catch(\Exception $e)
        // {
        //     $message = $e->getMessage();
        //     DB::rollback();
        //     return $this->jsonResponse(false, $message);
        // }
    }

    public function invoiceDetail(Request $request)
    {
        $invoiceId = $request->invoiceId;
        $initialInvoiceDetail = [];
        $finalInvoiceDetail = [];

        //get required data
        $invoices = DB::table('invoices')
            ->select('room_transactions.id As transaction_id', 'invoices.created_at As invoice_date', 'invoices.*', 'room_transactions.*', 'reservations.*', 'rooms.*', 'customers.*', 'room_categories.*')
            ->join('room_transactions', 'invoices.id', '=', 'room_transactions.invoice_id')
            ->join('reservations', 'reservations.id', '=', 'room_transactions.reservation_id')
            ->join('rooms', 'rooms.id', '=', 'reservations.room_id')
            ->join('customers', 'customers.id', '=', 'reservations.customer_id')
            ->join('room_categories', 'room_categories.id', '=', 'rooms.room_category_id')
            ->where('invoices.id', $invoiceId)
            ->get();

        // Parsing the string to array and decode back to array
        $encoded = json_encode($invoices, true);
        $decoded = json_decode($encoded, true);

        if (isset($decoded)) {
            foreach ($decoded as $invoice) {
                $initialInvoiceDetail =  array(
                    "callFrom" => "invoice",
                    "address" => $invoice['address'],
                    "amount" => number_format($invoice['total_amount'], 2),
                    "check_in_date" => $invoice['check_in_date'],
                    "check_out_date" => $invoice['check_out_date'],
                    "first_name" => $invoice['first_name'],
                    "middle_name" => $invoice['middle_name'],
                    "last_name" => $invoice['last_name'],
                    "invoice_number" => $invoice['invoice_number'],
                    "no_of_days" => $invoice['number_of_days'],
                    "rate" => $invoice['rate'],
                    "reservation_id" =>  $invoice['reservation_id'],
                    "transaction_id" => $invoice['transaction_id'],
                    "room_category" => $invoice['room_category'],
                    "room_number" => $invoice['room_number'],
                    "invoice_id" => $invoice['invoice_id'],
                    "service_charge" => $invoice['service_charge'],
                    "tax" => $invoice['tax'],
                    "vat" => $invoice['vat'],
                    "discount" => $invoice['discount'],
                    "sub_total" => number_format($invoice['sub_total'], 2),
                    "grand_total" => number_format($invoice['grand_total'], 2),
                    "created_at" => $invoice['invoice_date'],
                );
                array_push($finalInvoiceDetail, $initialInvoiceDetail);
            }
        }
        return $this->jsonResponse(true, 'List of invoice details.', $finalInvoiceDetail);
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
