<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'user_id' => $this->id,
            'event_id' => $this->Events->event_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'event' => $this->Events()->get()
        ];
    }
}