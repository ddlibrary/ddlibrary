<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ?string $guard = null): mixed
    {
        if (Auth::guard($guard)->check()) {
            if (! isAdmin()) {
                return redirect()->to('/home');
            }
        } else {
            return redirect()->to('/login');
        }

        return $next($request);
    }
}
