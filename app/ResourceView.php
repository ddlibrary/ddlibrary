<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ResourceView extends Model
{
    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }
}
