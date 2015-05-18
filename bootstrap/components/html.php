<?php

/**
 * VERBENA FRAMEWORK
 * 2015 - MIT LICENSE.
 */

namespace Bootstrap\Components;

use Bootstrap\Environment\Tracer;
use Bootstrap\Environment\Environment;
use Bootstrap\Exceptions\HTMLException;

class HTML {

    /**
     * Generate local url.
     *
     * @access  public
     * @param   string
     * @return  string
     */
    public static function url($_src = '') {

        if (strpos($_src, 'http://') === false and strpos($_src, 'https://') === false) {
            // Load environment variables.
            $_path_base = Environment::get_env('app.base_path');

            $_src = $_path_base . $_src;
        }

        return $_src;

    }

    /**
     * Generate stylesheet link.
     *
     * @access  public
     * @param   string
     * @param   array
     * @return  string
     */
    public static function style($_src = '', $_attributes = []) {

        $_add_attributes = static::_generate_attributes($_attributes);

        if (strpos($_src, 'http://') === false and strpos($_src, 'https://') === false) {
            // Load environment variables.
            $_env_app   = Environment::get_env('app');

            $_src = $_env_app['base_path'] . ltrim($_env_app['assets_path'], '/') . 'stylesheets/' . $_src . '.css';
        }

        return '<link rel="stylesheet" href="' . $_src . '"' . $_add_attributes . '>';

    }

    /**
     * Generate anchor tag with custom 
     * arguments.
     *
     * @access  public
     * @param   string
     * @param   string
     * @param   array
     * @return  string
     */
    public static function anchor($_src = '', $_text = '', $_attributes = []) {

        if (strpos($_src, 'http://') === false and strpos($_src, 'https://') === false) {
            // Load environment variables.
            $_path_base = Environment::get_env('app.base_path');
        
            $_src = $_path_base . $_src;
        }

        $_add_attributes = static::_generate_attributes($_attributes);

        return '<a href="' . $_src . '"' . $_add_attributes . '>' . $_text . '</a>';

    }


    /**
     * Generate image tag with custom
     * arguments.
     *
     * @access  public
     * @param   string
     * @param   array
     * @return  string
     */
    public static function image($_src = '', $_attributes = []) {

        // Load environment variables.
        $_env_app       = Environment::get_env('app');

        if (strpos($_src, 'http://') === false and strpos($_src, 'https://') === false) {
            $_src = $_env_app['base_path'] . ltrim($_env_app['assets_path'], '/') . 'images/' . $_src;
        }

        $_add_attributes = static::_generate_attributes($_attributes);

        return '<img src="' . $_src . '"' . $_add_attributes . '>';

    }

    /**
     * Return inline-code attributes list.
     *
     * @access  private
     * @param   array
     * @return  string
     */
    private static function _generate_attributes($_attributes = []) {

        $_add_attributes = '';

        // Add the custom attributes.
        if (!empty($_attributes)) {
            foreach ($_attributes as $_key => $_value) {
                $_add_attributes .= ' ' . $_key . '="' . $_value . '"';
            }
        }

        return $_add_attributes;

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
    
        Tracer::add("[[HTML:]] called an undefined method [['$name']]");

        try {
            throw new HTMLException("called an undefined method [['$name']]");
        }
        catch (HTMLException $exception) {
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

        Tracer::add("[[HTML:]] called an undefined method [['$name']]");

        try {
            throw new HTMLException("called an undefined method [['$name']]");
        }
        catch (HTMLException $exception) {
            echo $exception->get_formatted_exception();
        }
    
    }

}