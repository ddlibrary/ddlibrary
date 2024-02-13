<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaxonomyVocabulary extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    protected $primaryKey = 'vid';

    protected $table = 'taxonomy_vocabulary';

    public function term(): BelongsTo
    {
        return $this->belongsTo(TaxonomyTerm::class);
    }
}
