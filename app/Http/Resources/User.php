<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
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
            'id'        => $this->id,
            'username'  => $this->username,
            'password'  => $this->password,
            'email'     => $this->email,
            'status'    => $this->status,
            'language'  => $this->language,
            'accessed_at'  => $this->accessed_at,
            'created_at'  => $this->created_at,
            'updated_at'  => $this->updated_at,
            'updated_at'  => $this->updated_at,
            'user_id'=>$this->user_id,
            'role_id'=>$this->role_id

        ];
    }
}
