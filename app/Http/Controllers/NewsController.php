<?php

namespace App\Http\Controllers;
use App\News;
use Illuminate\Support\Facades\Auth;

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
    
    public function index ()
    {
        $this->middleware('admin');
        $newsRecords = News::orderBy('id','desc')->paginate(10);
        return view('admin.news.news_list', compact('newsRecords'));
    }

    public function view($newsId)
    {
        //setting the search session empty
        DDLClearSession();

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

    public function create()
    {
        //setting the search session empty
        DDLClearSession();
        
        return view('news.news_create');
    }

    public function store(Request $request, News $news)
    {
        $this->validate($request, [
            'title'      => 'required',
            'language'   => 'required',
            'summary'    => 'required',
            'body'       => 'required',
            'published'  => 'integer'
        ]);

        $news->title = $request->input('title');
        $news->summary = $request->input('summary');
        $news->body = $request->input('body');
        $news->language = $request->input('language');
        $news->user_id = Auth::id();
        $news->status = $request->input('published');
        //inserting
        $news->save();

        $news = News::find($news->id);
        $news->tnid = $news->id;
        //updating with tnid
        $news->save();

        return redirect('news/'.$news->id)->with('success', 'Item successfully created!');
    }

    public function edit(News $news, $id)
    {
        $news = $news->find($id);
        return view('news.news_edit', compact('news'));
    }

    public function update(Request $request, News $news, $id)
    {
        $this->validate($request, [
            'title'      => 'required',
            'language'   => 'required',
            'summary'    => 'required',
            'body'       => 'required',
            'published'  => 'integer'
        ]);

        $news = News::find($id);
        $news->title = $request->input('title');
        $news->summary = $request->input('summary');
        $news->body = $request->input('body');
        $news->language = $request->input('language');
        $news->user_id = Auth::id();
        $news->status = $request->input('published');
        //inserting
        $news->save();

        return redirect('news/'.$id)->with('success', 'Item successfully updated!');
    }

    public function translate(News $news, $id, $tnid)
    {   
        $news = $news->where('tnid', $tnid)->get();
        $news_self = $news->find($id);
        return view('news.news_translate', compact('news', 'news_self'));    
    }

    public function addTranslate($tnid, $lang)
    {
        return view('news.news_add_translate', compact('tnid', 'lang'));   
    }

    public function addPostTranslate(Request $request, News $news, $tnid, $lang)
    {
        $this->validate($request, [
            'title'      => 'required',
            'language'   => 'nullable',
            'summary'    => 'required',
            'body'       => 'required',
            'published'  => 'integer'
        ]);

        $news->title = $request->input('title');
        $news->summary = $request->input('summary');
        $news->body = $request->input('body');
        $news->language = $lang;
        $news->user_id = Auth::id();
        $news->tnid = $tnid;
        $news->status = $request->input('published');
        //inserting
        $news->save();

        return redirect('news/'.$news->id)->with('success', 'Item successfully updated!');    
    }
}
