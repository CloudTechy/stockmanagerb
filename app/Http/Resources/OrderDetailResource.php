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
            'product' => $this->product,
            'order_id' => $this->order_id,
            'brand' => $this->brand,
            'pku' => $this->pku,
            'size' => $this->size,
            'category' => $this->category,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'discount' => $this->discount,
            'amount' => $this->amount,

        ];
    }
}
