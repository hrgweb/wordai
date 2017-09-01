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
        if (Auth::guard($guard)->check()) {
            $user = $request->user();
            $user_level = (int) $user->user_level_id;

            // check if user is Admin
            if ($user_level === 2) {
                return redirect('admin');
            }

            // check if user is Writer
            if ($user_level === 3) {
                return redirect('writer');
            }

            // check if user is Editor
            if ($user_level === 4) {
                return redirect('editor');
            }
        }

        return $next($request);
    }
}
