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

    public function scopelistNews()
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

    public function oneNews($newsId)
    {
        $record = DB::table('news')
                ->select(
                    'newsid',
                    'title',
                    'summary',
                    'body',
                    'language',
                    'created',
                    'updated'
                )
                ->where('newsid',$newsId)
                ->first();

        return $record;
    }
}
