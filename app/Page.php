<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Page extends Model
{
    public function totalPages()
    {
        $records = DB::table('pages')
                    ->selectRaw('pages.pageid as totalPages')
                    ->count();
        return $records;
    }
}
