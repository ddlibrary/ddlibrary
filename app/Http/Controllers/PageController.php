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
        $pages = Page::paginate(10);
        return view('admin.pages.pages_list', compact('pages'));
    }

    function view($pageId)
    {
        $page = Page::find($pageId);

        $translation_id = $page->tnid;
        if($translation_id){
            $translations = Page::where('tnid',$translation_id)->get();
        }else{
            $translations = array();
        }

        return view('pages.pages_view', compact('page','translations'));
    }
}
