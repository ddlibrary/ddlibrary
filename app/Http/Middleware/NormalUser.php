<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NormalUser
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard($guard)->check()) {
            if (! isNormalUser()) {
                return redirect('/home');
            }
        } else {
            return redirect('/login');
        }

        return $next($request);
    }
}
