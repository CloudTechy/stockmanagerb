<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidatePurchaseDetailRequest extends FormRequest
{
    /**
     * Determine if the user is purhcaseDetailized to make this request.
     *
     * @return bool
     */
    public function purhcaseDetailize()
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

        $rules = [
            'purchaseDetails' => 'array|required',
            //'description' => 'required|string|min:3',
        ];
        if (!empty($this->input('purchaseDetails')) && is_array($this->input('purchaseDetails'))) {

            $purchaseDetails =  $this->input('purchaseDetails') ;
            $products = isset($purchaseDetails) ? $purchaseDetails : [];
           
            foreach ($products as $purhcaseDetail => $value) {

                $rules['purchaseDetails.' . $purhcaseDetail . '.purchase_id'] = 'required|numeric|exists:purchases,id';
                $rules['purchaseDetails.' . $purhcaseDetail . '.product'] = 'required|string|max:200|min:2';
                $rules['purchaseDetails.' . $purhcaseDetail . '.brand'] = 'required|string|exists:attributes,type';
                $rules['purchaseDetails.' . $purhcaseDetail . '.quantity'] = 'required|numeric';
                $rules['purchaseDetails.' . $purhcaseDetail . '.price'] = 'required|numeric';
                $rules['purchaseDetails.' . $purhcaseDetail . '.purchase_price'] = 'required|numeric';
                $rules['purchaseDetails.' . $purhcaseDetail . '.sale_price'] = 'required|numeric';
                $rules['purchaseDetails.' . $purhcaseDetail . '.percent_sale'] = 'numeric|min:0|max:100|nullable';
                $rules['purchaseDetails.' . $purhcaseDetail . '.pku'] = 'required|string|exists:units,name';
                $rules['purchaseDetails.' . $purhcaseDetail . '.size'] = 'required|string|exists:sizes,name';
                $rules['purchaseDetails.' . $purhcaseDetail . '.category'] = 'required|string|exists:categories,name';

            }

        }

        return $rules;

    }

    /* public function messages()
    {
    $messages = [];
    if (!empty($this->input('purchaseDetails')) && is_array($this->input('purchaseDetails'))) {

    foreach ($this->input('purchaseDetails') as $purhcaseDetail => $value) {
    $messages['purchaseDetails.' . $purhcaseDetail . '.*.required'] = ':attribute field is required.';
    $messages['purchaseDetails.' . $purhcaseDetail . '.*.max'] = 'field must be less than :max characters.';
    $messages['purchaseDetails.' . $purhcaseDetail . '.*.min'] = 'field must be at least :min characters.';
    $messages['purchaseDetails.' . $purhcaseDetail . '.*.exists'] = 'selected field is invalid';
    $messages['purchaseDetails.' . $purhcaseDetail . '.*.numeric'] = 'field must be a number.';
    $messages['purchaseDetails.' . $purhcaseDetail . '.*.string'] = 'field must be a string.';
    }

    }
    return $messages;

    }*/

    /*public function attributes()
{
return [
'thing.attribute' . $x => 'Nice Name',
];
}*/

}
