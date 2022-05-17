<?php

namespace App\Providers;
 
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use App\Libs\Interfaces\StripeHelperContract;
use App\Parsers\ParserContract;
 
class ParserServiceProvider extends ServiceProvider
{
    public function boot()
    {
        
    }
    
    public function register()
    {        
        App::singleton(ParserContract::class, function($app){
            return new \App\Parsers\ParserCurl();
        });
    }
}