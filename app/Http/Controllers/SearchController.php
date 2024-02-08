<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class SearchController extends Controller
{
    public function searchBar($args): View
    {
        return view('admin.common.search', $args);
    }
}
