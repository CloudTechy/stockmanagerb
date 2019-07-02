<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BankDetailResource extends JsonResource
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
            'bank' => $this->bank,
            'account_name' => $this->account_name,
            'account_number' => $this->account_number,
            'supplier_id' => $this->supplier_id,

        ];
    }
}
