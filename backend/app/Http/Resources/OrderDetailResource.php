<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

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
            'product' => $this->product . '-' . $this->size . '-' . $this->brand  . ' / ' . $this->category,
            'TOS' => $this->product . ' ' . $this->category . ' ' . $this->brand . ' ' . $this->size . ' ' . $this->created_at->format('Y-m-d H:i:s'),
            'name' => $this->product,
            'order_id' => $this->order_id,
            "image" => "default-150x150.png",
            'brand' => $this->brand,
            'pku' => $this->pku,
            'size' => $this->size,
            'user' => $this->order->user->last_name . ' ' .$this->order->user->first_name,
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
