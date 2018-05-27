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
        $records = DB::table('news')
                ->select(
                    'newsid',
                    'title',
                    'summary',
                    'body',
                    'language',
                    'tnid',
                    'created',
                    'updated'
                )
                ->where('language',Config::get('app.locale'))
                ->paginate(20);

        return $records;
    }

    public function oneNews($newsTnid)
    {
        $record = DB::table('news')
                ->select(
                    'newsid',
                    'title',
                    'summary',
                    'body',
                    'language',
                    'tnid',
                    'created',
                    'updated'
                )
                ->where('tnid',$newsTnid)
                ->where('language',Config::get('app.locale'))
                ->first();

        if($record){
            return $record;
        }else{
            return abort(404);
        }
    }
}
