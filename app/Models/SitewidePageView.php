<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
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

    public function platform(): BelongsTo
    {
        return $this->belongsTo(Platform::class);
    }

    public function browser(): BelongsTo
    {
        return $this->belongsTo(Browser::class);
    }

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }
}
