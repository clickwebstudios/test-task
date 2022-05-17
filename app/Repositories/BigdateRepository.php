<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\UserLog;
use App\Parsers\ParserContract;
use App\Models\Bigdate;

class BigdateRepository
{
    protected $lastResult = null;

    public function getLastResult()
    {
        return $this->lastResult;
    }
  
    public function updateMeta(string $site, ParserContract $parser)
    { 
        $model = app(Bigdate::class)->where('url', $site)->first()?? app(Bigdate::class);
        
        $model->fill([
          'url' => $site,
          'metaData' => $parser->getLastResult(),
        ])->save();
    }
}
