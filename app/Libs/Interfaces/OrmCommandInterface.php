<?php

namespace App\Libs\Interfaces;

Interface OrmCommandInterface 
{
    public function getErrors();

    function nextCommand();

    public function prevCommand();

    public function clear();

    public function setCommand($class, $function, array $args = []);

    public function setCommandAfterTransaction($class, $function, array $args = []);
    
    public function execute();
}
