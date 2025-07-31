<?php

namespace App\Http\Resources;

use App\AttributeProduct;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
        $variations = $this->variations;
        $supplier = new SupplierResource($this->supplier);
        $attributes = AttributeProduct::where('product_id', '=', $this->id)->get();

        return [

            "id" => $this->id,
            "name" => $this->name,
            "image_path" => $this->image,
            'description' => $this->description === null ? "No description yet" : $this->description,
            "category" => $this->category,
            "pku" => $this->pku,
            "discontinued" => $this->discontinued,
            "discount" => $this->discount,
            "discount_start" => $this->discount_start,
            "discount_end" => $this->discount_end,
            "added_by" => $user->first_name . ' ' . $user->last_name,
            "updated_by" => $this->updated_by,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'supplier_id' => $this->supplier_id,
            'supplier name' => $supplier->name,
            'attributes' => AttributeProductResource::collection($attributes),
            'total_stock' => $this->stock,
            'total_amount_sale' => $this->amountsale,
            'total_amount_purchase' => $this->amountPurchase,

        ];
    }
}
