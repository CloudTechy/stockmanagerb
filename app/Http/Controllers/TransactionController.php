<?php

namespace App\Http\Controllers;

use App\Helper;
use App\Http\Resources\TransactionResource;
use App\Invoice;
use App\Transaction;
use App\User;
use Illuminate\Http\Request;
use \DB;
use \Exception;

class TransactionController extends Controller
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

            $pageSize = request()->query('pageSize', 10000000000000000);
            $transactions = Transaction::filter(request()->all())
                ->latest()
                ->paginate($pageSize);

            $total = $transactions->total();

            $transactions = TransactionResource::collection($transactions);

            $data = Helper::buildData($transactions, $total);

            return Helper::validRequest($data, 'Transactions fetched successfully', 200);

        } catch (Exception $bug) {

            return $this->exception($bug, 'unknown error', 500);
        }
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
    public function store(Request $request)
    {

        if (!auth()->user()->activated) {
            return Helper::inValidRequest('User not activated', 'Unauthorized Access!', 400);
        }

        $validated = $request->validate(['invoice_id' => 'required|string|exists:invoices,id']);
        $validated['user_id'] = auth()->id();
        $request->except('updated_by');
        $transaction = "";
        if (Transaction::where('invoice_id', $validated['invoice_id'])->count() > 0) {
            $invoice = Invoice::find($validated['invoice_id']);
            $newTransaction = Transaction::where('invoice_id', $validated['invoice_id']);
            $type = $invoice->type;
            $validated['amount'] = $invoice->amount;

            if ($type == 'order') {
                $transaction = $newTransaction->update($validated);
            } elseif ($type == 'purchase') {
                $transaction = $newTransaction->update($validated);
            }
            DB::commit();

        }

        DB::beginTransaction();
        try
        {

            $invoice = Invoice::find($validated['invoice_id']);
            $type = $invoice->type;
            $validated['amount'] = $invoice->amount;
            $validated['status'] = 'not-paid';

            if ($type == 'order') {
                $owing = $invoice->order->customer->owing + $invoice->amount;
                $transaction = Transaction::create($validated);
                $invoice->order->customer->update(['owing' => $owing]);

            } elseif ($type == 'purchase') {
                $owed = $invoice->purchase->supplier->owed + $invoice->amount;
                $transaction = Transaction::create($validated);
                $invoice->purchase->supplier->update(['owed' => $owed]);
            }

            DB::commit();

        } catch (Exception $bug) {
            DB::rollback();
            return $this->exception($bug, 'unknown error', 500);
        }

        return Helper::validRequest(new TransactionResource($transaction), 'Transaction was sent successfully', 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Transaction $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        try {

            $transaction = new TransactionResource($transaction);

            return Helper::validRequest($transaction, 'specified Transaction was fetched successfully', 200);

        } catch (Exception $bug) {

            return $this->exception($bug, 'unknown error', 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Transaction $transaction
     * @return \Illuminate\Http\Response
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Transaction $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaction $transaction)
    {

        $request->only('payment', 'due_date');
        $validated['updated_by'] = auth()->user()->first_name . ' ' . auth()->user()->last_name;
        $validated = $request->validate(['payment' => 'required|numeric', 'due_date' => 'nullable|date']);
        $invoice = Invoice::find($transaction->invoice_id);
        $type = $invoice->type;
        $old_payment = $transaction->payment;
        $new_payment = $validated['payment'];
        $amount = $transaction->amount;
        $remaining_payment = $amount - $old_payment;

        try {
            if ($new_payment < $remaining_payment) {

                $validated['payment'] = $old_payment + $new_payment;
                $validated['status'] = 'pending';
                $transaction = $transaction->update($validated);
                if ($type == 'order') {
                    $owing = $invoice->order->customer->owing - $new_payment;
                    $invoice->order->customer->update(['owing' => $owing, 'due_date' => $validated['due_date']]);
                } elseif ($type == 'purchase') {
                    $owed = $invoice->purchase->supplier->owed - $new_payment;
                    $invoice->purchase->supplier->update(['owed' => $owed]);
                }

            } else {
                $balance = $new_payment - $remaining_payment;
                $validated['payment'] = $amount;
                $validated['status'] = 'paid';
                $validated['due_date'] = null;
                $invoice->update(['balance' => $balance]);

                $transaction = $transaction->update($validated);
                if ($type == 'order') {
                    $invoice->order->customer->update(['owing' => 0, 'due_date' => null]);
                } elseif ($type == 'purchase') {
                    $invoice->purchase->supplier->update(['owed' => 0, 'due_date' => null]);
                }

            }

            return Helper::validRequest(["success" => $transaction], 'Transaction was updated successfully', 200);

        } catch (Exception $bug) {

            return $this->exception($bug, 'unknown error', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Transaction $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        if (!auth()->user()->activated || !auth()->user()->isSuperAdmin) {
            return Helper::inValidRequest('User not Unauthorized or not Activated.', 'Unauthorized Access!', 400);
        }

        DB::beginTransaction();
        try {

            $transaction = $transaction->delete();

            DB::commit();

            return Helper::validRequest(["success" => $transaction], 'Transaction was deleted successfully', 200);

        } catch (Exception $bug) {
            DB::rollback();
            return $this->exception($bug, 'unknown error', 500);
        }

    }

}
