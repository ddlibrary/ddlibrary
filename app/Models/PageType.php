<?php

namespace App\Models;

use App\Models\Relations\HasManyPageVisit;
use Illuminate\Database\Eloquent\Model;

class PageType extends Model
{
    use HasManyPageVisit;

    protected $fillable = ['name'];
}
