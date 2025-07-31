<?php

namespace App\Http\Resources;

use App\Transaction;
use Illuminate\Http\Resources\Json\JsonResource;


class PurchaseDetailsResource extends JsonResource
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
        $transaction = Transaction::where('invoice_id', $this->invoiceID)->first();

        return [

            'id' => $this->id,
            'supplier_name' => $this->supplier_name,
            'user' => $user->first_name . ' ' . $user->last_name,
            'comment' => $this->comment,
            'updated_by' => $this->updated_by,
            'supplier_id' => $this->supplier_id,
            'user_id' => $this->user_id,
            'invoice_id' => $this->invoiceID,
            'transaction_id' => empty($transaction) ? null : $transaction->id,
            'purchaseDetails' => PurchaseDetailResource::collection($this->purchaseDetails),
            'Total_amount' => $this->amount,
            'Total_quantity' => $this->quantity,

        ];
    }
}
