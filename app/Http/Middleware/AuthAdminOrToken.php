<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthAdminOrToken
{
    public function handle($request, Closure $next, $guard = null)
    {
        $token = request()->token;
        $user = null;
        
        if ($token) {
            $user = User::where('access_token', $token)->first();
        }
        
        if ($user) {
            auth()->onceUsingId($user->id);
            return $next($request);
        }
        
        if ($token and !$user) {
            return response()->json([
                'error' => 'access Denied'
            ]);
        }
        
        if (Auth::guard($guard)->check()) {

            $user = Auth::user();

            if ($user) {
                return $next($request);
            }
        }

        return redirect(route('auth.index'));
    }
}
