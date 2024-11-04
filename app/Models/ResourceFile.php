<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class ResourceFile extends Model
{
    use HasFactory;

    protected $fillable = ['uuid', 'name', 'license', 'path', 'thumbnail_path', 'language'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
    }

    public function resources(): HasMany
    {
        return $this->hasMany(Resource::class);
    }
}
