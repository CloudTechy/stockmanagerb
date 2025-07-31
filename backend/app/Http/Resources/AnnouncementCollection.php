<?php

namespace App\Http\Resources;

use App\Http\Resources\User as UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class AnnouncementCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        return [
            'id' => $this->id,
            "topic" => $this->topic,
            "message" => $this->message,
            "active" => $this->active,
            "auto_publish" => $this->auto_publish,
            "published" => $this->published,
            "date_start" => $this->date_start,
            "date_end" => $this->date_end,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),

            "user" => new UserResource($this->user),

        ];
    }
}
