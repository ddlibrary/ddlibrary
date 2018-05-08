<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class News extends Model
{
    public function totalNews()
    {
        $records = DB::table('news')
                    ->selectRaw('news.newsid as totalNews')
                    ->count();
        return $records;
    }

    public function listNews()
    {
        $records = DB::table('news')
                ->select(
                    'newsid',
                    'title',
                    'summary',
                    'body',
                    'language',
                    'created',
                    'updated'
                )
                ->paginate(20);

        return $records;
    }
}
