<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
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
            'invoice_id' => $this->invoice_id,
            'user' => $this->user->first_name . ' ' . $this->user->last_name,
            'user_id' => $this->user_id,
            'amount' => $this->amount,
            'payment' => $this->payment,
            'status' => $this->status,
            'type' => $this->invoice->type,
            'due' => $this->due,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::createFromTimeStamp(strtotime($this->updated_at))->diffForHumans(),
            'date' => Carbon::createFromTimeStamp(strtotime($this->created_at))->diffForHumans(),

        ];
    }
}
