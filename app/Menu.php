<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Menu extends Model
{
    public function scopeMenu()
    {
        $records = DB::table('menus')
            ->select(
                'menuid',
                'parent',
                'title',
                'weight',
                'language',
                'created',
                'updated'
            )
            ->paginate(20);
        return $records;
    }
}
