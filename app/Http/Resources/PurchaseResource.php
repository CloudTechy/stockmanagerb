<?php

namespace App\Http\Resources;

use App\Transaction;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $user = new User($this->user);
        $supplier = new SupplierResource($this->supplier);
        // $invoice = Invoice::where('purchase_id', $this->id)->first();
        //  empty($invoice) ? null : $invoice->id
        $transaction = Transaction::where('invoice_id', $this->invoiceID)->first();
        //dd($transaction->id);

        return [

            'id' => $this->id,
            'supplier_name' => $supplier->name,
            'user' => $user->first_name . ' ' . $user->last_name,
            'comment' => $this->comment,
            'updated_by' => $this->updated_by,
            'supplier_id' => $this->supplier_id,
            'user_id' => $this->user_id,
            'invoice_id' => $this->invoiceID,
            'transaction_id' => empty($transaction) ? null : $transaction->id,
            'purchaseDetails' => purchaseDetailResource::collection($this->purchaseDetails),
            'Total_amount' => $this->amount,
            'Total_quantity' => $this->quantity,

        ];
    }
}
