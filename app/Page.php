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

    public function scopelistPages()
    {
        $records = DB::table('pages')
                ->select(
                    'pages.pageid',
                    'pd.title',
                    'pd.summary',
                    'pd.body',
                    'pd.language',
                    'pd.tnid',
                    'pd.created',
                    'pd.updated'
                )
                ->join('pages_data as pd','pd.pageid','=','pages.pageid')
                ->paginate(20);

        return $records;
    }

    public function onePage($pageId)
    {
        $record = DB::table('pages')
                ->select(
                    'pages.pageid',
                    'pd.title',
                    'pd.summary',
                    'pd.body',
                    'pd.language',
                    'pd.tnid',
                    'pd.created',
                    'pd.updated'
                )
                ->join('pages_data as pd','pd.pageid','=','pages.pageid')
                ->where('pd.pageid',$pageId)
                ->first();

        return $record;    
    }

    public function getPageTranslations($pageId)
    {
        $record = DB::table('pages AS p')
            ->select(
                'p.pageid AS id',
                'pd.language'
            )
            ->leftJoin('pages_data AS pd','pd.pageid','=','p.pageid')
            ->where('pd.tnid', $pageId)
            ->get();
        return $record;
    }
}