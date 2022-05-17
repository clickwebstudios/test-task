<?php

namespace App\Models;

use App\ModelExt;

class Bigdate extends ModelExt
{
    protected $table = "bigdates";

    public $timestamps = true;

    protected $fillable = [
        'url',
        'metaData',
    ];

    protected $casts = [
        'url' => 'integer',
        'metaData' => 'json',
    ];
}
