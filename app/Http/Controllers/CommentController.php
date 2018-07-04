<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comment;

class CommentController extends Controller
{
    public function index()
    {
        $comments = Comment::comments();
        return view('admin.comments.comments_list', compact('comments'));
    }
}
