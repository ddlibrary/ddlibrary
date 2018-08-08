<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaxonomyTerm extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
    
    protected $table = 'taxonomy_term_data';
    
    public function vocabulary()
    {
        return $this->hasOne(TaxonomyVocabulary::class, 'vid', 'vid');   
    }

    public function scopeName($query, $name)
    {
        if (!is_null($name)) {
            return $query->where('name','like', '%'.$name.'%');
        }
    }

    public function scopeVocabulary($query, $vid)
    {
        if (!is_null($vid)) {
            return $query->where('vid', $vid);
        }
    }

    public function scopeLanguage($query, $language)
    {
        if (!is_null($language)) {
            return $query->where('language', $language);
        }
    }
}
