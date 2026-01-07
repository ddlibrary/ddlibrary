<?php

namespace App\Http\Controllers;

use App\Models\ResourceComment;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CommentController extends Controller
{
    public function index(): View
    {
        $comments = ResourceComment::orderBy('id', 'DESC')->paginate(10);

        return view('admin.comments.comments_list', compact('comments'));
    }

    public function published(ResourceComment $resourceComment)
    {
        $resourceComment->update([
            'status' => $resourceComment->status == 1 ? 0 : 1,
        ]);

        return back();
    }

    public function delete(ResourceComment $resourceComment): RedirectResponse
    {
        $resourceComment->delete();

        return redirect()->back()->with('success', 'Comment has been deleted successfully!');
    }
}
