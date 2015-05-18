<?php

/**
 * VERBENA FRAMEWORK
 * 2015 - MIT LICENSE.
 */

namespace Bootstrap\Models;

use Bootstrap\Environment\Tracer;
use Bootstrap\Exceptions\ModelException;

class Model {

    protected static $_instances = [];


    /**
     * Create the model instance.
     * (unused method at the moment)
     *
     * @access  public
     * @param   void
     * @return  object
     */
    public static function instance_class() {
        
        $_class = get_called_class();

        if (!isset(static::$_instances[$_class])) {
            static::$_instances[$_class] = new $_class();
        }

        return static::$_instances[$_class];

    }

    /**
     * Magic method that will be called when trying
     * to call a class method that doesn't exists.
     *
     * @access  public
     * @param   string
     * @param   array
     * @return  exception
     */
    public function __call($name, $arguments) {
    
        Tracer::add("[[Model:]] called an undefined method [['$name']]");

        try {
            throw new ModelException("called an undefined method [['$name']]");
        }
        catch (ModelException $exception) {
            echo $exception->get_formatted_exception();
        }

    }

    /**
     * Magic method that will be called when trying
     * to call statically a class method that 
     * doesn't exists.
     *
     * @access  public
     * @param   string
     * @param   array
     * @return  exception
     */
    public static function __callStatic($name, $arguments) {

        Tracer::add("[[Model:]] called an undefined method [['$name']]");

        try {
            throw new ModelException("called an undefined method [['$name']]");
        }
        catch (ModelException $exception) {
            echo $exception->get_formatted_exception();
        }
    
    }

}