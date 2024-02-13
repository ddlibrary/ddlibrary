<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sync extends Model
{
    public function getResources()
    {
        $remoteResources = new Resource();
        $remoteResources->setConnection('mysql_remote');

        return $remoteResources->all();
    }
}
