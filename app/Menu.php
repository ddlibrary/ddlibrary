<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * @method static orderBy(string $string)
 */
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

    public function scopeTitle($query, $title)
    {
        if (!is_null($title)) {
            return $query->where('title','like', '%'.$title.'%');
        }
    }

    public function scopeLocation($query, $location)
    {
        if (!is_null($location)) {
            return $query->where('location', $location);
        }
    }

    public function scopeLanguage($query, $language)
    {
        if (!is_null($language)) {
            return $query->where('language', $language);
        }
    }
}
