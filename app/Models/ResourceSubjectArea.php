<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @method static where(string $string, int|string $id)
 */
class ResourceSubjectArea extends Model
{
    use HasFactory;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    protected $fillable = ['resource_id', 'tid'];

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function icon(): HasOne
    {
        return $this->hasOne(StaticSubjectIcon::class, 'tid', 'tid');
    }
}
