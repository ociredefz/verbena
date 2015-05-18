<?php

/**
 * VERBENA FRAMEWORK
 * 2015 - MIT LICENSE.
 */

namespace Bootstrap\Autoloader;

use Bootstrap\Environment\Tracer;
use Bootstrap\Environment\Environment;
use Bootstrap\Exceptions\AutoloaderException;

class Autoloader {

    protected static $_registered = false;


    /**
     * Load the classes by splitting the namespace
     * into the filesystem path.
     *
     * @access  private
     * @param   string
     * @return  void
     */
    private static function _load_class($_class) {

        Tracer::add('[[Loader:]] loading class [[\\' . $_class . ']]');

        // Split the namespace into filesystem format.
        $_back_class = $_class;
        $_class = str_replace('\\', '/', strtolower(ltrim($_class, '\\')));

        // Combine the absolute file path.
        $_abs_path  = Environment::get_abs_path();
        $_abs_file  = $_abs_path . '/' . $_class . '.php';

        // If the load of a class by namespace will fails,
        // it tries to find the class file by research it in
        // the filesystem (this is a fall-back method).
        if (file_exists($_abs_file) === false) {
            Tracer::add('[[Loader:]] class file not found, switching to fallback method');

            // Get the array value of the real filename.
            $_abs_file  = preg_grep('/(\w).(php)/', explode('/', $_abs_file));

            if (!empty($_abs_file)) {
                $_found = null;
                $_abs_file  = static::_fs_search('bootstrap/.*', current($_abs_file), $_found);

                // The file was found in filesystem.
                if (!is_null($_found)) {
                    $_abs_file = $_abs_path . '/' . $_found;
                    
                    $_back_class = str_replace('/', '\\', $_found);
                    $_back_class = str_replace('.php', '', $_back_class);
                }
            }
        }

        // Cast the return as boolean.
        try {
            if (!class_exists("\\".$_back_class)) {
                if (!is_readable($_abs_file)) {
                    throw new AutoloaderException('class file not found [[' . $_back_class . ']]');
                }
                else {
                    return !!include $_abs_file;
                }
            }
            else {
                throw new AutoloaderException('cannot redeclare class [[' . $_back_class . ']]. <br>' . 
                    '[[Missing namespace declaration?!]]');
            }
        }
        catch (AutoloaderException $exception) {
            echo $exception->get_formatted_exception();
            exit;
        }

    }

    /**
     * Focuse the class research in the filesystem.
     * This can be useful because allows you to skip the 
     * declaration of the namespaces above the controller.
     *
     * @access  public
     * @param   string
     * @param   string
     * @param   mixed
     * @return  void
     */
    public static function _fs_search($_pattern, $_filename, &$_found) {

        // The file was found, returns now.
        if (!is_null($_found)) {
            return $_found;
        }

        // Search in the directory and sub-directory for file.
        foreach (glob(dirname($_pattern) . '/*', GLOB_NOSORT) as $_pointer) {
            // Check if it is a valid file.
            if (is_file($_pointer) !== false) {

                // Get array value of the real filename.
                $_exp_file  = explode('/', $_pointer);
                $_file = preg_grep('/(\w).(php)/', $_exp_file);

                if (!empty($_file)) {
                    $_file  = current($_file);

                    // Is the file we're searching?
                    if ($_file == $_filename) {
                        $_found = $_pointer;
                    }
                }
            }

            static::_fs_search($_pointer . '/' . basename($_pattern), $_filename, $_found);
        }

    }

    /**
     * Register the autoloader to load classes
     * with the namespace and register the aliases.
     *
     * @access  public
     * @param   void
     * @return  bool
     */
    public static function register_class() {

        // Return if it is already registered.
        if (static::$_registered) {
            return false;
        }

        Tracer::add('[[Loader:]] registering autoload functions');
        static::$_registered = spl_autoload_register('Bootstrap\Autoloader\Autoloader::_load_class');

        // Generate the namespaces aliases.
        $_components = Environment::get_env('components.aliases');
        
        foreach ($_components as $_key => $_value) {
            if (class_exists($_value)) {
                class_alias($_value, $_key);
            }
        }


        return static::$_registered;

    }

    /**
     * Load the shutdown function that will be 
     * start at the end of script's execution.
     *
     * @access  private
     * @param   void
     * @return  void
     */
    private static function _load_shutdown() {

        Tracer::add('[[Loader:]] script end with the register shutdown function');

    }

    /**
     * Register the shutdown function.
     *
     * @access  public
     * @param   void
     * @return  void
     */
    public static function register_shutdown() {

        register_shutdown_function(function() {
            static::_load_shutdown();
        });

    }

}