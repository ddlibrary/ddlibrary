<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Config;

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
        $records = DB::table('news AS nw')
                ->select(
                    'nw.newsid',
                    'nwd.title',
                    'nwd.summary',
                    'nwd.body',
                    'nwd.language',
                    'nwd.created',
                    'nwd.updated'
                )
                ->join('news_data AS nwd', 'nwd.newsid','=','nw.newsid')
                ->where('nwd.language',Config::get('app.locale'))
                ->paginate(20);

        return $records;
    }

    public function oneNews($newsTnid)
    {
        $record = DB::table('news AS nw')
                ->select(
                    'nw.newsid',
                    'nwd.title',
                    'nwd.summary',
                    'nwd.body',
                    'nwd.language',
                    'nwd.created',
                    'nwd.updated',
                    'nwt.translation_id'
                )
                ->join('news_data AS nwd', 'nwd.newsid','=','nw.newsid')
                ->leftJoin('news_translations AS nwt', 'nwt.newsid','=','nw.newsid')
                ->where('nw.newsid',$newsTnid)
                ->where('nwd.language',Config::get('app.locale'))
                ->first();

        return $record;
    }

    public function getNewsTranslations($newsId)
    {
        $record = DB::table('news AS nw')
            ->select(
                'nw.newsid AS id',
                'nwd.language'
            )
            ->leftJoin('news_data AS nwd','nwd.newsid','=','nw.newsid')
            ->leftJoin('news_translations AS nwt','nwt.newsid','=','nw.newsid')
            ->where('nwt.translation_id', $newsId)
            ->get();
        return $record;
    }
}
