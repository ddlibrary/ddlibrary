<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class LibraryManager
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ?string $guard = null): Response
    {
        if (Auth::guard($guard)->check()) {

            if (! isAdmin() and ! isLibraryManager()) {
                return redirect()->to('/home');
            }
        } else {
            return redirect()->to('/login');
        }

        return $next($request);
    }
}
