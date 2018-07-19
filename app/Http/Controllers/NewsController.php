<?php

namespace App\Http\Controllers;
use App\News;

use Illuminate\Http\Request;

class NewsController extends Controller
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
        $newsRecords = News::paginate(10);
        return view('admin.news.news_list', compact('newsRecords'));
    }

    function view($newsId)
    {
        $myNews = new News();

        $news = News::find($newsId);
        $translation_id = $news->tnid;
        if($translation_id){
            $translations = News::where('tnid',$translation_id)->get();
        }else{
            $translations = array();
        }
        return view('news.news_view', compact('news','translations'));
    }
}
