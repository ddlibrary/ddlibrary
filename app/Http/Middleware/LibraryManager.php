<?php

namespace App\Http\Middleware;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Closure;
use Illuminate\Support\Facades\Auth;

class LibraryManager
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $guard = null): Response
    {
        if (Auth::guard($guard)->check()) {

            if (! isAdmin() and ! isLibraryManager()) {
                return redirect('/home');
            }
        } else {
            return redirect('/login');
        }

        return $next($request);
    }
}
