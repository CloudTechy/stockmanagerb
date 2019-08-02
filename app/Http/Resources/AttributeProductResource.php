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

        return [

            'id' => $this->id,
            'product_id' => $this->product_id,
<<<<<<< HEAD
            'product' => $this->product->name . '-' . $this->product->category . '-' . $this->attribute->type . '-' . $this->size,
            'TOS' => $this->product->name . ' ' . $this->product->category . ' ' . $this->attribute->type . ' ' . $this->size . ' ' . $this->created_at->format('Y-m-d H:i:s') . ' ' . $this->updated_at->format('Y-m-d H:i:s'),
=======
>>>>>>> a90f05ca68e2264c685a9477281ef51e4d16983b
            'name' => $this->product->name,
            'brand' => $this->attribute->type,
            'category' => $this->product->category,
            'size' => $this->size,
            'unit' => $this->product->pku,
            'image' => $this->product->image,
            'description' => $this->product->description,
            'purchase_price' => $this->purchase_price,
            "price" => $this->price,
            'amount' => $this->amount,
            'discount' => $this->product->discount,
            'discount_start' => $this->product->discount_start,
            'discount_end' => $this->product->discount_end,
            'stock' => $this->available_stock,
            'discontinued' => $this->product->discontinued,
<<<<<<< HEAD
            "added_by" => empty($this->updated_by) ? $this->user->first_name . ' ' . $this->user->last_name : $this->updated_by,
            "updated_by" => $this->updated_by,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'date' => Carbon::createFromTimeStamp(strtotime($this->updated_at))->diffForHumans(),
=======
            "added_by" => $this->user->first_name . ' ' . $this->user->last_name,
            "updated_by" => $this->updated_by,
            'date' => Carbon::createFromTimeStamp(strtotime($this->created_at))->diffForHumans(),
>>>>>>> a90f05ca68e2264c685a9477281ef51e4d16983b

        ];
    }
}
