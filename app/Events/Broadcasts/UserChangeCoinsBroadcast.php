<?php

namespace App\Events\Broadcasts;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Models\User;

class UserChangeCoinsBroadcast implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
  
    public $user;
    
    public function __construct(User $user)
    {
      $this->user = $user;
    }
    
    public function broadcastOn()
    {
        return ['user.coins.'.$this->user->id];
    }
	
    public function broadcastAs()
    {
        return 'changed';
    }
}
