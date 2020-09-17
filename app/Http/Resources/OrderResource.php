<?php

namespace App\Http\Resources;

use App\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
        // $customer = new CustomerResource($this->customer);
        // $transaction = Transaction::where('invoice_id', $this->invoiceID)->first();

        return [

            'id' => $this->id,
            // 'customer_name' => $customer->name,
            // 'user' => $user->first_name . ' ' . $user->last_name,
            'TOS' => $this->customer_name . ' ' . $this->staff . ' ' . $this->created_at->format('d-m-Y-H-i'),
            'comment' => $this->comment,
            'updated_by' => $this->updated_by,
            'customer_id' => $this->customer_id,
            'customer_name' => $this->customer_name,
            'user' => $this->staff,
            'user_id' => $this->user_id,
            'invoice_id' => $this->invoiceId,
            'transaction_id' => empty($transaction) ? null : $transaction->id,
            // 'status' => empty($transaction) ? null : $transaction->status,
            'Total_amount' => $this->amount,
            'Total_quantity' => $this->quantity,
            'created_at' => $this->created_at->format('Y-m-d'),
            'updated_at' => $this->updated_at->format('Y-m-d'),
            'date' => Carbon::createFromTimeStamp(strtotime($this->created_at))->diffForHumans(),
        ];
    }
}
