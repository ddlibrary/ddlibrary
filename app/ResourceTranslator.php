<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ResourceTranslator extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
    
    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }
}
