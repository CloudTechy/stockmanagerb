<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidateProductRequest extends FormRequest
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

            "name" => "required|string|min:3|max:200|unique:products",
            "category" => "required|string|exists:categories,name",
            'pku' => 'required|string|exists:units,name',
            "discount" => 'numeric|digits_between:0,100',
            "discount_start" => 'required_unless:discount,|date_format:Y-m-d H:i:s.u|before_or_equal:date_end',
            "discount_end" => 'required_unless:discount,|date_format:Y-m-d H:i:s.u|after_or_equal:date_start',
            'description' => "string|max:500",
            'supplier_id' => "required|numeric|exists:suppliers,id",

            'attribute_id' => "required|numeric|exists:attributes,id",
            'size' => 'required|string|exists:sizes,name',
            'purchase_price' => 'required|numeric',
            'percent_sale' => 'numeric|digits_between:1,100',
            'sale_price' => 'required|numeric',
            'available_stock' => 'required|numeric',
        ];
    }
}
