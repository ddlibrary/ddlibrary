<?php

namespace App\Models;

use App\Models\Relations\HasManyGlossaryPageView;
use App\Models\Relations\HasManySitewidesPageView;
use Illuminate\Database\Eloquent\Model;

class Platform extends Model
{
    use HasManySitewidesPageView, HasManyGlossaryPageView;

    protected $fillable = ['name'];
}
