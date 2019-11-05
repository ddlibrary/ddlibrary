<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Resource extends JsonResource
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
            'title' => $this->title,
            'abstract' => $this->abstract,
            'language' => $this->language,
            'user_id' => $this->user_id,
            'status' => $this->status,
            'tnid' => $this->tnid,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
