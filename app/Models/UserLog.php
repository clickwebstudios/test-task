<?php

namespace App\Models;

use App\ModelExt;

class UserLog extends ModelExt
{
    protected $table = "user_logs";

    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'message',
        'price',
        'data',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'price' => 'integer',
        'data' => 'json',
    ];
}
