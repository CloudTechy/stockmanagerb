<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidateCustomerRequest extends FormRequest
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
<<<<<<< HEAD
            "name" => "required|string|min:2|max:255",
=======
            "name" => "required|string|min:6|max:255",
>>>>>>> a90f05ca68e2264c685a9477281ef51e4d16983b
            "number" => "required|string|min:3|max:255|unique:customers,number",
            "notes" => "string|max:150",
            "owning" => "numeric",

        ];
    }
}
