<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Resource;

class ResourceController extends Controller
{
    public function index()
    {
        $resources = Resource::resources();
        return view('admin.resources',compact('resources'));
    }
}
