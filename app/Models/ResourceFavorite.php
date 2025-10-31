<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static where(string $string, int|null $id)
 */
class ResourceFavorite extends Model
{
    use HasFactory;

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }
}
