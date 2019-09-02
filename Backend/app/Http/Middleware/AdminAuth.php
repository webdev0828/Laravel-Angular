<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next,$guard = null)
    {
        if (Auth::guard($guard)->guest()) {
          if ($request->ajax()) {
            return response('Unauthorized.', 401);
          } else {
            return redirect()->guest('admin/login');
          }
        } else if (!in_array(Auth::guard($guard)->user()->user_type,['admin','admin_user'])) {
          return redirect()->guest('admin/login');//->to('/')->withError('Permission Denied');
        }

        return $next($request);
    }
}
