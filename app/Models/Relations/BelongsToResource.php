<?php

namespace App\Models\Relations;

use App\Models\Resource;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToResource
{
    /**
     * Get resource
     */
    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }
}