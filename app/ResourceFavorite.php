<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, int|null $id)
 */
class ResourceFavorite extends Model
{
    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }
}
