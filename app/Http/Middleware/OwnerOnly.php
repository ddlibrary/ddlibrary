<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OwnerOnly
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $resource_id = $request->route('resourceId');
        $user_id = DB::table('resources')->find($resource_id)->user_id;
        if ($request->user()->id != $user_id) {
            abort(403, __('Unauthorized action.'));
        }

        return $next($request);
    }
}
