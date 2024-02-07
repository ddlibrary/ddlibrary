<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static find($fileId)
 * @method static findOrFail($fileId)
 *
 * @property bool|mixed file_watermarked
 * @property mixed      file_name
 */
class ResourceAttachment extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    protected $fillable = ['resource_id', 'file_name', 'file_mime', 'file_size', 'file_watermarked'];

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }
}
