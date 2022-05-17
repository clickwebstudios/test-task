<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\UserBalance;
use App\Models\UserPayment;
use App\Models\UserLog;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];
    
    protected $with = ['userBalance'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    public function userBalance()
    {
        return $this->hasOne(UserBalance::class, 'user_id');
    }
    
    public function userLogs()
    {
        return $this->hasMany(UserLog::class, 'user_id')->orderBy('id', 'desc');
    }
    
    public function userPayments()
    {
        return $this->hasMany(UserPayment::class, 'user_id');
    }
    
    public function userPaymentDefault()
    {
        return $this->hasOne(UserPayment::class, 'user_id')->where('is_default', 1);
    }
}
