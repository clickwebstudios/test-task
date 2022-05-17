<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AuthAdmin
{
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {

            $user = Auth::user();

            if ($user) {
                return $next($request);
            }
        }

        return redirect(route('auth.index'));
    }
}
