<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function searchBar($args)
    {
        return view('admin.common.search', $args);
    }
}
