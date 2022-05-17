<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Libs\OrmCommand;
use App\Libs\Interfaces\OrmCommandInterface;
 
class OrmCommandServiceProvider extends ServiceProvider
{
    public function boot()
    {
        
    }
    
    public function register()
    {
        $this->app->singleton(OrmCommandInterface::class, OrmCommand::class);
        $this->app->alias(OrmCommandInterface::class, 'OrmCommand');
    }
}