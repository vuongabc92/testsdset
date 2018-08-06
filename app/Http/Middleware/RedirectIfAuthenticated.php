<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $urls = [
            route('front_login'),
            route('front_register')
        ];

        if (Auth::guard($guard)->check() && in_array($request->fullUrl(), $urls)) {
            return redirect(route('front_settings'));
        }

        return $next($request);
    }
}
