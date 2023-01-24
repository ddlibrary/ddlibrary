<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * @method static orderBy(string $string)
 * @method static find(string $id)
 * @method distinct()
 * @method static max(string $string)
 * @property array|mixed|string|null     title
 * @property array|mixed|string|null     parent
 * @property array|mixed|string|null     path
 * @property array|mixed|string|null     location
 * @property array|mixed|string|null     language
 * @property array|mixed|string|null     weight
 * @property array|int|mixed|string|null tnid  // translation id (translations are chained)
 * @property mixed $status
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
