<?php

namespace App\Http\Resources;

use App\Transaction;
use Carbon\Carbon;
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
        // $user = new User($this->user);
        // $supplier = new SupplierResource($this->supplier);
        // $transaction = Transaction::where('invoice_id', $this->invoiceID)->first();

        return [

            'id' => $this->id,
            // 'supplier_name' => $supplier->name,
            // 'user' => $user->first_name . ' ' . $user->last_name,
            'TOS' => $this->supplier_name . ' ' . $this->staff . ' ' . $this->created_at->format('d-m-Y H:i'),
            'comment' => $this->comment,
            'updated_by' => $this->updated_by,
            'supplier_id' => $this->supplier_id,
            'supplier_name' => $this->supplier_name,
            'user_id' => $this->user_id,
            'user' => $this->staff,
            'invoice_id' => $this->invoiceID,
            'transaction_id' => empty($transaction) ? null : $transaction->id,
            // 'purchaseDetails' => purchaseDetailResource::collection($this->purchaseDetails),
            'Total_amount' => $this->amount,
            'Total_quantity' => $this->quantity,
            'created_at' => $this->created_at->format('Y-m-d'),
            'updated_at' => $this->updated_at->format('Y-m-d'),
            'date' => Carbon::createFromTimeStamp(strtotime($this->created_at))->diffForHumans(),

        ];
    }
}
