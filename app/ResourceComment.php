<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ResourceComment extends Model
{
    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
