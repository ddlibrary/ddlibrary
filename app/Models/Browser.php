<?php

namespace App\Models;

use App\Models\Relations\HasManyGlossaryPageView;
use App\Models\Relations\HasManySitewidePageView;
use Illuminate\Database\Eloquent\Model;

class Browser extends Model
{
    use HasManyGlossaryPageView, HasManySitewidePageView;

    protected $fillable = ['name'];
}
