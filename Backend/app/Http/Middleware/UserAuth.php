<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class UserAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->guest()) {
            
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                return response('Unauthorized.', 401);
            }

        } else if (!in_array(Auth::guard($guard)->user()->user_type, ['stm_user','artist'])) {

            return response('Unauthorized.', 401);

        }

        return $next($request);
        
    }
}
