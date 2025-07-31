<?php

namespace App\Http\Resources;

use App\Http\Resources\User as UserResource;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerDetails extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */

    public function toArray($request)
    {
        $user = new UserResource($this->user);
        return [
            'id' => $this->id,
            "name" => $this->name,
            "number" => $this->number,
            "email" => $this->email,
            "notes" => $this->notes,
            "owing" => $this->owing,
            "due_date" => $this->due_date,
            "added_by" => $user->first_name . ' ' . $user->last_name,
            "updated_by" => $this->updated_by,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'date' => Carbon::createFromTimeStamp(strtotime($this->created_at))->diffForHumans(),
            'orders' => $this->orders,

        ];
    }
}
