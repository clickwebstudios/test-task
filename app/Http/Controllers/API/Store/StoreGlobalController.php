<?php

namespace App\Http\Controllers\API\Store;

use App\Http\Controllers\Controller;

class StoreGlobalController extends Controller
{
    public function get()
    {
        return response()->json([
            'user' => auth()->user()
        ]);
    }
}
