<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
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
            "name" => $this->name,
            "number" => $this->number,
            "email" => $this->email,
            "owing" => $this->owing,
            "due_date" => $this->due_date,
            "added_by" => $this->staff,
            "updated_by" => $this->updated_by,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'date' => Carbon::createFromTimeStamp(strtotime($this->created_at))->diffForHumans(),

        ];
    }
}
