<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class ResourceFile extends Model
{
    use HasFactory;

    protected $fillable = ['label', 'taxonomy_term_data_id', 'name', 'language', 'resource_id'];

    public function resources(): HasMany
    {
        return $this->hasMany(Resource::class);
    }
}
