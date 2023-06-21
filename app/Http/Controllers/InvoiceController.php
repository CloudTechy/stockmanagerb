<?php

namespace App\Http\Controllers;

use App\Helper;
use App\Http\Requests\ValidateInvoiceRequest;
use App\Http\Resources\InvoiceResource; 
use App\Http\Resources\InvoiceDetails; 
use App\Invoice;
use App\Jobs\ProcessInvoice;
use App\Order;
use App\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \Exception;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        try {
            $page = request()->query('page', 1);

            $pageSize = request()->query('pageSize', 10000000);

            $invoices = Invoice::filter(request()->all())
                ->latest()
                ->paginate($pageSize);

            $total = $invoices->total();

            $invoices = InvoiceResource::collection($invoices);

            $data = Helper::buildData($invoices, $total);

        } catch (\Exception $bug) {

            return $this->exception($bug, 'unknown error', 500);
        }
        return Helper::validRequest($data, 'Invoices fetched successfully', 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ValidateInvoiceRequest $request)
    {
        $invoice_created = " ";
        $validated = $request->validated();
        if (!auth()->user()->activated) {
            return Helper::inValidRequest('User not activated', 'Unauthorized Access!', 400);
        }
        $validated['user_id'] = auth()->id();

        DB::beginTransaction();

        try
        {
            if ($validated['type'] == 'order' && !empty($validated['order_id'])) {
                $order = Order::find($validated['order_id']);
                $invoice['type'] = $validated['type'];
                $invoice['order_id'] = $validated['order_id'];
                $invoice['user_id'] = $validated['user_id'];
                $invoice['amount'] = $order->amount;
                $invoice['cost'] = $order->cost;
                $validated['amount'] = $order->amount;

                $invoice_order = Invoice::where('order_id', $validated['order_id']);
                if ($invoice_order->count() != 0) {

                    if ($invoice_order->first()->update($invoice)) {

                        $invoice_created = Invoice::find($invoice_order->first()->id);
                    }

                } else {
                    $invoice_created = Invoice::create($validated);
                }
            } elseif ($validated['type'] == 'purchase' && !empty($validated['purchase_id'])) {
                $purchase = Purchase::find($validated['purchase_id']);
                $invoice['type'] = $validated['type'];
                $invoice['purchase_id'] = $validated['purchase_id'];
                $invoice['user_id'] = $validated['user_id'];
                $invoice['amount'] = $purchase->amount;
                $invoice['cost'] = $purchase->amount;
                $validated['amount'] = $purchase->amount;

                $invoice_purchase = Invoice::where('purchase_id', $validated['purchase_id']);
                if ($invoice_purchase->count() != 0) {

                    if ($invoice_purchase->first()->update($invoice)) {

                        $invoice_created = Invoice::find($invoice_purchase->first()->id);
                    }

                } else {
                    $invoice_created = Invoice::create($validated);
                }
            } else {

                return Helper::inValidRequest('Your type field should match either purchase_id field or order_id field', 'Bad Request', 400);

            }
            if (!Helper::createTransaction($invoice_created)) {

                throw new Exception("Error Processing transaction request", 1);
            }
            ProcessInvoice::dispatch();
            DB::commit();
            return Helper::validRequest(new InvoiceDetails($invoice_created), 'Invoice was sent successfully', 200);

        } catch (\Exception $bug) {
            DB::rollback();
            return $this->exception($bug, 'unknown error', 500);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Invoice $invoice
     * @return \Illuminate\Http\Response
     */
    public function show(Invoice $invoice)
    {

        try {

            $invoice = new InvoiceDetails($invoice);

            return Helper::validRequest($invoice, 'specified Invoice was fetched successfully', 200);

        } catch (\Exception $bug) {

            return $this->exception($bug, 'unknown error', 500);
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Invoice $invoice
     * @return \Illuminate\Http\Response
     */
    public function edit(Invoice $invoice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Invoice $invoice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Invoice $invoice)
    {

        return Helper::inValidRequest('Operation Disabled', 'Bad Request', 400);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Invoice $invoice
     * @return \Illuminate\Http\Response
     */
    public function destroy(Invoice $invoice)
    {
        if (!Helper::userIsSuperAdmin()) {
            return Helper::inValidRequest('User not Unauthorized or not Activated.', 'Unauthorized Access!', 400);
        }
        DB::beginTransaction();
        try {

            $invoice = $invoice->delete();
            ProcessInvoice::dispatch();

            DB::commit();

        } catch (\Exception $bug) {
            DB::rollback();
            return $this->exception($bug, 'unknown error', 500);
        }
        return Helper::validRequest(["success" => $invoice], 'Invoice was deleted successfully', 200);
    }
}
