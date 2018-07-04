<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Flag extends Model
{
    public function scopeFlags()
    {
        $records = DB::table('resources_flags AS rf')
                ->select(
                    'rf.id',
                    'rf.resourceid',
                    'rf.userid',
                    'rd.title',
                    'users.username',
                    'rf.type',
                    'rf.details',
                    'rf.created',
                    'rf.updated'
                )
                ->LeftJoin('resources_data AS rd', 'rf.resourceid','=','rd.resourceid')
                ->LeftJoin('users', 'users.id', '=', 'rf.userid')
                ->paginate(10);

        return $records;
    }
}
