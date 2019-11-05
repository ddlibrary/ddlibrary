<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Resource;

class Sync extends Model
{
    public function getResources()
    {
        $remoteResources = new Resource();
        $remoteResources->setConnection('mysql_remote');
        return $remoteResources->all();
    }
}
