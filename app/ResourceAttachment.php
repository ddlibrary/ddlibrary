<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ResourceAttachment extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    protected $fillable = ['resource_id','file_name', 'file_mime', 'file_size'];
    
    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }
}
