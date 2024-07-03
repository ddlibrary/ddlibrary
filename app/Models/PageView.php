<?php

namespace App\Models;

use App\Models\Relations\BelongsToUser;
use Illuminate\Database\Eloquent\Model;

class PageView extends Model
{
    use BelongsToUser;
    
    protected $guarded = [];

    protected $casts = [
        'is_bot' => 'boolean',
    ];

    public function pageType()
    {
        return $this->belongsTo(PageType::class);
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
