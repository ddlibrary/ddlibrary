<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResourceTranslationLink extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function linkedResource(): BelongsTo {
        return $this->belongsTo(Resource::class, 'link_resource_id');
    }
}
