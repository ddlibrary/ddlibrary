<?php

namespace App\Http\Middleware;

use Closure;

class LibraryManager
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
            if(!isLibraryManager()){
                return redirect('/home');
            }
        }else{
            return redirect('/login');
        }

        return $next($request);
    }
}
