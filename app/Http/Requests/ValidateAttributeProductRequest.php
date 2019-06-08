<?php

namespace App\Http\Requests;

use App\AttributeProduct;
use Illuminate\Foundation\Http\FormRequest;

class ValidateAttributeProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'product_id' => "required|string|exists:products,id",
            'attribute_id' => "required|numeric|exists:attributes,id",
            'size' => 'required|string|exists:sizes,name|multiple_unique:' . AttributeProduct::class . ',product_id,size,attribute_id',
            'purchase_price' => 'required|numeric',
            'percent_sale' => 'numeric|digits_between:1,100',
            'sale_price' => 'required|numeric',
            'available_stock' => 'required|numeric',
        ];
    }
}
