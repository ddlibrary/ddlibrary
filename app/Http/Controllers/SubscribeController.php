<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SubscribeRequest;
use App\Models\Subscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class SubscribeController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        $subscriber = $request->user() ? Subscriber::whereUserId(auth()->id())->first() : null;

        if ($subscriber) {
            $request->session()->flash('alert', [
                'message' => trans('You are already subscribed to our newsletter.'),
                'level' => 'success',
            ]);

            return redirect('/home');
        }

        return view('subscribe.index', compact('subscriber'));
    }

    public function store(SubscribeRequest $request): RedirectResponse
    {
        Subscriber::create([
            'email' => $request->email,
            'name' => $request->name,
            'user_id' => $request->user() ? Auth::id() : null,
        ]);

        $request->session()->flash('alert', [
            'message' => trans('Thank you for subscribing to our newsletter! You will now receive updates and news directly in your inbox.'),
            'level' => 'success',
        ]);

        return redirect('home');
    }
}
