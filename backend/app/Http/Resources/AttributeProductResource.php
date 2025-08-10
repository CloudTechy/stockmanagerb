<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class AttributeProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // Use whenLoaded to avoid N+1 queries
        $product = $this->whenLoaded('product');
        $attribute = $this->whenLoaded('attribute');
        $user = $this->whenLoaded('user');

        return [
            'id' => $this->id,
            'product_id' => $this->product_id,

            'product' => $this->size . ' ' . 
                         optional($attribute)->type . ' ' . 
                         optional($product)->category . ' - ' . 
                         optional($product)->name,

            'TOS' => optional($product)->name . ' ' . 
                     optional($product)->category . ' ' . 
                     optional($attribute)->type . ' ' . 
                     $this->size . ' ' . 
                     $this->created_at->format('Y-m-d H:i:s') . ' ' . 
                     $this->updated_at->format('Y-m-d H:i:s'),

            'name' => optional($product)->name,
            'brand' => optional($attribute)->type,
            'category' => optional($product)->category,
            'size' => $this->size,
            'unit' => optional($product)->pku,
            'image' => optional($product)->image,
            'description' => optional($product)->description,
            'purchase_price' => $this->purchase_price,
            'price' => (float) $this->price,
            'amount' => $this->amount,
            'discount' => optional($product)->discount,
            'discount_start' => optional($product)->discount_start,
            'discount_end' => optional($product)->discount_end,
            'stock' => $this->available_stock,
            'discontinued' => optional($product)->discontinued,

            'added_by' => empty($this->updated_by)
                ? (optional($user)->first_name . ' ' . optional($user)->last_name)
                : $this->updated_by,

            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'date' => Carbon::createFromTimeStamp(strtotime($this->updated_at))->diffForHumans(),
        ];
    }
}
