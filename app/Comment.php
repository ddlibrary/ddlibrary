<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Comment extends Model
{
    public function scopeComments()
    {
        $records = DB::table('resources_comments AS rc')
                ->select(
                    'rc.id',
                    'rc.resourceid',
                    'rc.userid',
                    'rd.title',
                    'users.username',
                    'rc.comment',
                    'rc.status',
                    'rc.created',
                    'rc.updated'
                )
                ->LeftJoin('resources_data AS rd', 'rc.resourceid','=','rd.resourceid')
                ->LeftJoin('users', 'users.id', '=', 'rc.userid')
                ->paginate(10);

        return $records;
    }
}
