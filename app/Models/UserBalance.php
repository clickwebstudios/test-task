<?php

namespace App\Models;

use App\ModelExt;

class UserBalance extends ModelExt
{
    protected $table = "user_balances";

    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'coins',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'coins' => 'integer',
    ];
}
