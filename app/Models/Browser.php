<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Relations\HasManyGlossaryPageView;
use App\Models\Relations\HasManySitewidePageView;
use Illuminate\Database\Eloquent\Model;

class Browser extends Model
{
    use HasFactory;

    use HasManySitewidePageView, HasManyGlossaryPageView;

    protected $fillable = ['name'];
}
