<?php

namespace App\Libs;

use Exception;

class ExceptionCommand extends Exception 
{
    protected $_arrayErrors = array();

    public function __construct($message = NULL, array $array = [], $code = 0) 
    {
        $this->_arrayErrors = $array;
        parent::__construct($message, (int) $code);
    }

    public function getErrors() 
    {
        return $this->_arrayErrors;
    }

}
