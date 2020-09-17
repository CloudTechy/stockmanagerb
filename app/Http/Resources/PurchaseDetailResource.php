<?php

namespace App\Http\Resources;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        return [

            'id' => $this->id,
            'product' => $this->product . '-' . $this->category . '-' . $this->brand . '-' . $this->size,
            'TOS' => $this->product . ' ' . $this->category . ' ' . $this->brand . ' ' . $this->size . ' ' . $this->created_at->format('Y-m-d H:i:s'),
            'name' => $this->product,
            'purchase_id' => $this->purchase_id,
            'invoice_id' => $this->purchase->invoice_id,
            'vendor' => $this->purchase->supplier_name,
            'user' => $this->purchase->user->username,
            'brand' => $this->brand,
            'pku' => $this->pku,
            'size' => $this->size,
            'category' => $this->category,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'percent_sale' => $this->percent_sale,
            'amount' => $this->amount,
            'status' => empty($this->purchase->invoice->transaction) ? null : $this->purchase->invoice->transaction->status,
            'payment' => empty($this->purchase->invoice->transaction) ? 0 : $this->purchase->invoice->transaction->payment,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'date' => Carbon::createFromTimeStamp(strtotime($this->created_at))->diffForHumans(),

        ];
    }
}
