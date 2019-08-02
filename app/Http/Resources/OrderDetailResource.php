<?php

namespace App\Http\Resources;

<<<<<<< HEAD
use Carbon\Carbon;
=======
>>>>>>> a90f05ca68e2264c685a9477281ef51e4d16983b
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
<<<<<<< HEAD
            'product' => $this->product . '-' . $this->category . '-' . $this->brand . '-' . $this->size,
            'TOS' => $this->product . ' ' . $this->category . ' ' . $this->brand . ' ' . $this->size . ' ' . $this->created_at->format('Y-m-d H:i:s'),
            'name' => $this->product,
=======
            'product' => $this->product,
>>>>>>> a90f05ca68e2264c685a9477281ef51e4d16983b
            'order_id' => $this->order_id,
            'brand' => $this->brand,
            'pku' => $this->pku,
            'size' => $this->size,
<<<<<<< HEAD
            'customer' => $this->order->customer->name,
            'user' => $this->order->user->username,
            'invoice_id' => $this->order->invoice_id,
            'status' => empty($this->order->invoice->transaction) ? null : $this->order->invoice->transaction->status,
            'payment' => empty($this->order->invoice->transaction) ? 0 : $this->order->invoice->transaction->payment,
=======
>>>>>>> a90f05ca68e2264c685a9477281ef51e4d16983b
            'category' => $this->category,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'discount' => $this->discount,
            'amount' => $this->amount,
<<<<<<< HEAD
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'date' => Carbon::createFromTimeStamp(strtotime($this->created_at))->diffForHumans(),
=======
>>>>>>> a90f05ca68e2264c685a9477281ef51e4d16983b

        ];
    }
}
