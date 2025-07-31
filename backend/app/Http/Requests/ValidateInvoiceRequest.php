<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidateInvoiceRequest extends FormRequest
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
            'type' => 'required|string|exists:types,name',
            'purchase_id' => 'required_without:order_id|numeric|exists:purchases,id',
            'order_id' => 'required_without:purchase_id|string|exists:orders,id',
        ];
    }
}
