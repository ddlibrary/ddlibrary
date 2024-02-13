<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaxonomyHierarchy extends Model
{
    public $timestamps = false;

    protected $table = 'taxonomy_term_hierarchy';

    protected $fillable = ['tid'];
}
