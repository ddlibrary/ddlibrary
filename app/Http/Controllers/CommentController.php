<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ResourceComment;

class CommentController extends Controller
{
    public function index()
    {
        $comments = ResourceComment::paginate(10);
        return view('admin.comments.comments_list', compact('comments'));
    }
}
