<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ResourceIamAuthor extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    protected $table = "resource_iam_author";
    
    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }
}
