<?php

namespace App\Http\Middleware;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Closure;

class NormalUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
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
