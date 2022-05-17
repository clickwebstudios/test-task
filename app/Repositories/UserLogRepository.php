<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\UserLog;
use App\Parsers\ParserContract;

class UserLogRepository
{
    protected $lastResult = null;

    public function getLastResult()
    {
        return $this->lastResult;
    }
  
    public function logsByUser(User $user)
    {
        return $user->userLogs->toArray();
    }
    
    public function store(User $user, ParserContract $parser, string $message, int $price)
    { 
        $model = app(UserLog::class);
        
        $model->fill([
          'user_id' => $user->id,
          'message' => $message,
          'price' => $price,
          'data' => $parser->getLastResult(),
        ])->save();
    }
}
