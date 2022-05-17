<?php

namespace App\Repositories;

use App\Models\User;
use App\Libs\Interfaces\StripeHelperContract;
use App\Libs\ExceptionCommand;
use App\Models\UserBalance;
use App\Events\Broadcasts\UserChangeCoinsBroadcast;

class UserBalanceRepository
{
    protected $lastResult = null;

    public function getLastResult()
    {
        return $this->lastResult;
    }
  
    public function subtractMeta(User $user)
    {
        $balance = $user->userBalance?? app(UserBalance::class);
        $coinsCurrent = $balance->coins?? 0;
        
        $itogCoins = $coinsCurrent - config('saas.price_meta');
        
        if ($itogCoins < 0) {
            Throw new ExceptionCommand('Insufficient funds', ['Insufficient funds']);
        }
        
        $balance->coins = $itogCoins;
        $balance->user_id = $user->id;
        $balance->save();
        
        //event(new UserChangeCoinsBroadcast($user));
    }
    
    public function addCoins(User $user, int $coins)
    {
        $balance = $user->userBalance?? app(UserBalance::class);
        
        $coinsCurrent = $balance->coins?? 0;
        
        $balance->coins = $coinsCurrent + $coins;
        $balance->user_id = $user->id;
        $balance->save();
        
        event(new UserChangeCoinsBroadcast($user));
    }
}
