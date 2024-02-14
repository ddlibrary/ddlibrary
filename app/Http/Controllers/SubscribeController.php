<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscribeController extends Controller
{
    public function subscribe(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email|unique:subscribers,email',
            'name' => 'required|string'
        ]);

        Subscriber::create([
            'email' => $request->email,
            'name' => $request->name,
            'user_id' => Auth::check() ? Auth::id() : null,
        ]);

        return back()->with('success', 'Thank you for subscribing!');
    }
}
