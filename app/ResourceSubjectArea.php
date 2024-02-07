<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, int|string $id)
 */
class ResourceSubjectArea extends Model
{
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
