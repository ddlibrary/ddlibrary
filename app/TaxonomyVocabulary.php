<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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

    public function term()
    {
        return $this->belongsTo(TaxonomyTerm::class);
    }
}
