<?php

namespace App\Models;

use App\Models\Relations\BelongsToUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SitewidePageView extends Model
{
    use BelongsToUser, HasFactory;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'is_bot' => 'boolean',
        ];
    }

    public function platform()
    {
        return $this->belongsTo(Platform::class);
    }

    public function browser()
    {
        return $this->belongsTo(Browser::class);
    }

    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}
