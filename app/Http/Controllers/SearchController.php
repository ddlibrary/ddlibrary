<?php

namespace App\Http\Controllers;

class SearchController extends Controller
{
    public function searchBar($args)
    {
        return view('admin.common.search', $args);
    }
}
