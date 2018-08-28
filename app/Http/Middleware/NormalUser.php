<?php

namespace App\Http\Middleware;

use Closure;

class NormalUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::guard($guard)->check()) {
            if(!isNormalUser()){
                return redirect('/home');
            }
        }else{
            return redirect('/login');
        }

        return $next($request);
    }
}
