<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaxonomyHierarchy extends Model
{
    public $timestamps = false;
    protected $table = 'taxonomy_term_hierarchy';
}
