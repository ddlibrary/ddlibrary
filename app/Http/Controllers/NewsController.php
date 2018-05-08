<?php

namespace App\Http\Controllers;
use App\News;

use Illuminate\Http\Request;

class NewsController extends Controller
{
    function index ()
    {
        $myNews = new News();
        $newsRecords = $myNews->listNews();
        return view('admin.news.news_list', compact('newsRecords'));
    }
}
