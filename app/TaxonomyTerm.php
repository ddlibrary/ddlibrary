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
    //
}
