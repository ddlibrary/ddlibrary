<?php

namespace App\Models;

use App\Models\Relations\HasManySitewidesPageView;
use Illuminate\Database\Eloquent\Model;

class PageType extends Model
{
    use HasManySitewidesPageView;

    protected $fillable = ['name'];
}
