<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use Auth;

class AuthController extends Controller
{
    public function logout()
    {
        auth()->logout();
    }
  
    public function login()
    {
        $wasAttempt = Auth::attempt([
            'name' => request()->login,
            'password' => request()->password,
        ]);
      
        if (!$wasAttempt) {
            abort(403);
        }
    }
}
