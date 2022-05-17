<?php

namespace App\Providers;
 
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use App\Libs\Interfaces\StripeHelperContract;
 
class StripeHelperServiceProvider extends ServiceProvider
{
    public function boot()
    {
        
    }
    
    public function register()
    {        
        App::singleton(StripeHelperContract::class, function($app){
            return new \App\Libs\StripeHelper();
        });
    }
}