<?php

namespace App\Models;

use App\Models\Relations\BelongsToUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GlossaryPageView extends Model
{
    use BelongsToUser;
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'is_bot' => 'boolean',
    ];

    public function glossarySubject()
    {
        return $this->belongsTo(GlossarySubject::class);
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
