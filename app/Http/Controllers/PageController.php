<?php

namespace App\Http\Controllers;
use App\Page;

use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }
    
    function index ()
    {
        $this->middleware('admin');
        $myPages = new Page();
        $pages = $myPages->listPages();
        return view('admin.pages.pages_list', compact('pages'));
    }

    function view($pageId)
    {
        $myPage = new Page();
        $page = $myPage->onePage($pageId);
        return view('pages.pages_view', compact('page'));
    }
}
