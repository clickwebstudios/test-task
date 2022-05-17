<?php

namespace App\Models;

use App\ModelExt;
use App\Libs\Interfaces\StripeHelperContract;
use App\Models\User;

class UserPayment extends ModelExt
{
    protected $table = "user_payments";

    public $timestamps = true;
  
    protected $fillable = [
        'user_id',
        'stripe_payment_id',
        'stripe_payment_attache_id',
    ];

    protected $casts = [
        'is_default' => 'integer',
        'user_id' => 'integer',
        'cvs' => 'integer',
    ];
    
    protected $appends = [
        'cardCode',
        'cardBrand',
    ];
    
    public function getCardCodeAttribute()
    {
        if (!$this->stripe_payment_id) {
            return null;
        }
      
        if ($this->stripe_payment_id === $this->stripe_payment_id_cache) {
            return $this->stripe_payment_card_cache;
        }
        
        $stripe = app(StripeHelperContract::class);
        $payment = $stripe->getPaymentInformation($this->stripe_payment_id);
      
        $this->stripe_payment_id_cache = $this->stripe_payment_id;
        $this->stripe_payment_card_cache = $payment['card']?? null;
        $this->stripe_payment_brand_cache = $payment['brand']?? null;
        $this->save();
        
        return $this->stripe_payment_card_cache;
    }
    
    public function getCardBrandAttribute()
    {
        $this->cardCode;
      
        return mb_strtoupper($this->stripe_payment_brand_cache);
    }
    
    public function user() 
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
