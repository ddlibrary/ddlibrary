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
     */
    public function handle(Request $request, Closure $next, ?string $guard = null): Response
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
