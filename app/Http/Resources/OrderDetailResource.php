<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailResource extends JsonResource
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
            'order_id' => $this->order_id,
            'brand' => $this->brand,
            'pku' => $this->pku,
            'size' => $this->size,
            'customer' => $this->order->customer->name,
            'user' => $this->order->user->username,
            'invoice_id' => $this->order->invoice_id,
            'status' => empty($this->order->invoice->transaction) ? null : $this->order->invoice->transaction->status,
            'payment' => empty($this->order->invoice->transaction) ? 0 : $this->order->invoice->transaction->payment,
            'category' => $this->category,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'discount' => $this->discount,
            'amount' => $this->amount,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'date' => Carbon::createFromTimeStamp(strtotime($this->created_at))->diffForHumans(),

        ];
    }
}
