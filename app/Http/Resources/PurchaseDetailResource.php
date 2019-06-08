<?php

namespace App\Http\Resources;

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
            'product' => $this->product,
            'purchase_id' => $this->purchase_id,
            'brand' => $this->brand,
            'pku' => $this->pku,
            'size' => $this->size,
            'category' => $this->category,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'percent_sale' => $this->percent_sale,
            'amount' => $this->amount,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),

        ];
    }
}
