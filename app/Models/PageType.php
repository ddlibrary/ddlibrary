<?php

namespace App\Models;

use App\Models\Relations\HasManyPageView;
use Illuminate\Database\Eloquent\Model;

class PageType extends Model
{
    use HasManyPageView;

    protected $fillable = ['name'];
}
