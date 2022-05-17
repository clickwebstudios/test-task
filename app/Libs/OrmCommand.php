<?php

namespace App\Libs;

use DB;
use App\Libs\Interfaces\OrmCommandInterface;

class OrmCommand implements OrmCommandInterface
{
    protected $_classes = array();
    protected $_methods = array();
    protected $_args = array();
    protected $_classesAfterTrans = array();
    protected $_methodsAfterTrans = array();
    protected $_argsAfterTrans = array();
    protected $_errors = array();

    function __construct() 
    {
        
    }
    
    public function getCommands()
    {
        return [
          'classes' => $this->_classes,
          'methods' => $this->_methods,
          'args' => $this->_args,
        ];
    }
    
    public function getErrors() 
    {
        return $this->_errors;
    }

    public function nextCommand()
    {
        Throw new \Exception('not work');
    }

    public function prevCommand()
    {
        Throw new \Exception('not work');
    }

    public function clear()
    {
        $this->_classes = array();
        $this->_methods = array();
        $this->_args = array();

        $this->_classesAfterTrans = array();
        $this->_methodsAfterTrans = array();
        $this->_argsAfterTrans = array();

        $this->_errors = array();
        return $this;
    }

    public function setCommand($class, $function, array $args = array()) 
    {
        if (!method_exists($class, $function)) {
            Throw new \Exception("Model: '" . get_class($class) . "' does not have a method: '" . $function . "'");
        }

        $this->_classes[] = $class;
        $this->_methods[] = $function;
        $this->_args[] = $args;
        return $this;
    }

    public function setCommandAfterTransaction($class, $function, array $args = array()) 
    {
        if (!method_exists($class, $function)) {
            Throw new \Exception("Model: '" . get_class($class) . "' does not have a method: '" . $function . "'");
        }

        $this->_classesAfterTrans[] = $class;
        $this->_methodsAfterTrans[] = $function;
        $this->_argsAfterTrans[] = $args;

        return $this;
    }

    public function execute() 
    {
        try {

            DB::beginTransaction();

            foreach ($this->_classes as $k => $class) {
                call_user_func_array(array($class, $this->_methods[$k]), $this->_args[$k]);
            }

            DB::commit();

            foreach ($this->_classesAfterTrans as $k => $class) {
                call_user_func_array(array($class, $this->_methodsAfterTrans[$k]), $this->_argsAfterTrans[$k]);
            }

            return true;
        } catch (\Illuminate\Database\QueryException $e) {
            $this->_errors = [$e->getMessage()];
        } catch (\Illuminate\Validation\ValidationException $e) {
    
            $this->_errors = $e->errors()->messages();

        } catch (\App\Libs\ExceptionCommand $e) {
            $this->_errors = $e->getErrors();
        }

        DB::rollback();
        return false;
    }

}
