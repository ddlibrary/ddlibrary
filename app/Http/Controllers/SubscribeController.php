<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubscribeRequest;
use App\Models\Subscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SubscribeController extends Controller
{
    public function index(): View
    {
        $subscriber = Auth::check() ? Subscriber::whereUserId(auth()->id())->first() : null;

        return view('subscribe.index', compact('subscriber'));
    }

    public function store(SubscribeRequest $request): RedirectResponse
    {
        Subscriber::create([
            'email' => $request->email,
            'name' => $request->name,
            'user_id' => Auth::check() ? Auth::id() : null,
        ]);

        return back()->with('success', 'Thank you for subscribing!');
    }
}
