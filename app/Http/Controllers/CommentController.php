<?php

namespace App\Http\Controllers;

use App\Models\ResourceComment;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CommentController extends Controller
{
    public function index(): View
    {
        $comments = ResourceComment::orderByDesc('id')->paginate(10);

        return view('admin.comments.comments_list', compact('comments'));
    }

    public function published($commentId)
    {
        $this->middleware('admin');

        $rs = ResourceComment::find($commentId);
        if ($rs->status == 1) {
            $rs->status = 0;
            $rs->save();
        } else {
            $rs->status = 1;
            $rs->save();
        }

        return redirect()->back();
    }

    public function delete($commentId): RedirectResponse
    {
        $comment = ResourceComment::findOrFail($commentId);
        $comment->delete();

        return redirect()->back()->with('success', 'Comment has been deleted successfully!');
    }
}
