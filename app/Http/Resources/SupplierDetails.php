<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class SupplierDetails extends JsonResource
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

        return [
            "id" => $this->id,
            "name" => $this->name,
            "address" => $this->address,
            "city" => $this->city,
            "phone" => $this->number,
            "email" => $this->email,
            "country" => $this->country,
            "owed" => $this->owed,
            "due_date" => $this->due_date,
            'contact_person' => $this->contact_person === null ? $this->name : $this->contact_person,
            'is_stock_available' => $this->is_stock_available,
            "added_by" => $user->first_name . ' ' . $user->last_name,
            "updated_by" => $this->updated_by,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'date' => Carbon::createFromTimeStamp(strtotime($this->created_at))->diffForHumans(),

            'bank_details' => BankDetailResource::collection($this->bankDetails),
            'purchases' => $this->purchases,
        ];
    }
}
