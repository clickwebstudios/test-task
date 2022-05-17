<?php

namespace App\Console\Commands;

use File;
use Illuminate\Routing\Router;
use Illuminate\Console\Command;

class GenerateRoutesForJavascript extends Command
{
    protected $signature = 'route:json';
    
    protected $description = 'Generate routes for javascript.';
    
    protected $router;
    
    public function __construct(Router $router)
    {
        parent::__construct();
        
        $this->router = $router;
    }
    
    public function handle()
    {
        $routes = [];
        foreach ($this->router->getRoutes() as $route) {
            $routes[$route->getName()] = $route->uri();
        }
        
        File::put('resources/js/datas/routes.json', json_encode($routes, JSON_PRETTY_PRINT));
    }
}