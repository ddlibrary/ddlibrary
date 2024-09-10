<?php

namespace App\Models;

use App\Models\Relations\BelongsToUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GlossaryPageView extends Model
{
    use BelongsToUser;

    protected $guarded = [];

    protected $casts = [
        'is_bot' => 'boolean',
    ];

    public function glossarySubject(): BelongsTo
    {
        return $this->belongsTo(GlossarySubject::class);
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
