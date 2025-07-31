<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidateSupplierRequest extends FormRequest
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
            "name" => "required|string|min:2|max:255|unique:suppliers,name",
            "address" => "nullable|string|min:4",
            "city" => "nullable|string|max:255",
            "number" => "required|string|min:3|max:255|unique:suppliers,number",
            "email" => "email|required|min:3|unique:suppliers,email",
            "owed" => "numeric",
            "country" => "nullable|string|min:3|max:255",
            "is_stock_available" => "nullable|boolean",
            "bank_name" => "nullable|string",
            "acc_number" => "required_with:bank_name|nullable|string|min:10",
            "acc_name" => "required_with:bank_name|nullable|string|min:4",
        ];
    }
}
