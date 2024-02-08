<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResourceIamAuthor extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    protected $table = 'resource_iam_author';

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }
}
