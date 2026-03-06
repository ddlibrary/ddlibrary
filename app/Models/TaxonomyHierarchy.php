<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxonomyHierarchy extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'taxonomy_term_hierarchy';

    protected $fillable = ['id', 'tid', 'parent'];
}
