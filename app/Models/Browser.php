<?php

namespace App\Models;

use App\Models\Relations\HasManyPageVisit;
use Illuminate\Database\Eloquent\Model;

class Browser extends Model
{
    use HasManyPageVisit;

    protected $fillable = ['name'];
}
