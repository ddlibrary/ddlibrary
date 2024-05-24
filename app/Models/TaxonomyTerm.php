<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @method static findOrFail($id)
 */
class TaxonomyTerm extends Model
{
    use HasFactory;
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    protected $table = 'taxonomy_term_data';

    public function vocabulary(): HasOne
    {
        return $this->hasOne(TaxonomyVocabulary::class, 'vid', 'vid');
    }

    public function scopeName($query, $name)
    {
        if (! is_null($name)) {
            return $query->where('name', 'like', '%'.$name.'%');
        }
    }

    public function scopeVocabulary($query, $vid)
    {
        if (! is_null($vid)) {
            return $query->where('vid', $vid);
        }
    }

    public function scopeLanguage($query, $language)
    {
        if (! is_null($language)) {
            return $query->where('language', $language);
        }
    }
}
