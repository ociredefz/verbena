<?php

/**
 * VERBENA FRAMEWORK
 * 2015 - MIT LICENSE.
 */

namespace Bootstrap\Environment;

use Bootstrap\Environment\Tracer;

class Environment {

    /**
     * Environment container.
     * @var array
     */
    protected static $_env = [];


    /**
     * Compare the PHP version and checks 
     * the minimum required version.
     * (PHP >= 5.4.0)
     */
    public static function requirements() {

        if (version_compare(PHP_VERSION, '5.4.0', '<')) {
            echo 
                '<p>The current <b>PHP</b> (' . PHP_VERSION . ') <b>version</b> is <b>not supported</b>!<br>' .
                'You must <b>upgrade</b> to a <b>PHP version >= 5.4.0</b> in order to use verbena.</p>' .
                '<p><u>Note that verbena is a modern framework and it uses some features<br>' .
                'that were introduced in the recent versions of PHP.</u></p>';
            exit;
        }

    }

    /**
     * Create the start-up environment.
     * Create a list of absolute paths and 
     * loads the base configuration files.
     *
     * @access  public
     * @param   string
     * @return  void
     */
    public static function create($_abs_path) {

        // Define the configuration base files and app paths.
        $_data = [
            'abs'       => $_abs_path,
            'config'    => [
                'app'           => $_abs_path . '/app/config/app.php',
                'routes'        => $_abs_path . '/app/config/routes.php',
                'session'       => $_abs_path . '/app/config/session.php',
                'database'      => $_abs_path . '/app/config/database.php',
                'providers'     => $_abs_path . '/app/config/providers.php',
                'components'    => $_abs_path . '/app/config/components.php',
                'security'      => $_abs_path . '/app/config/security.php',
                'cache'         => $_abs_path . '/app/config/cache.php',
                'mail'          => $_abs_path . '/app/config/mail.php'
            ],
            'paths'     => [
                'storage'       => $_abs_path . '/app/storage/',
                'cache'         => $_abs_path . '/app/storage/cache/',
                'language'      => $_abs_path . '/app/language/',
                'views'         => $_abs_path . '/app/views/',
                'includes'      => $_abs_path . '/app/views/includes/',
                'layouts'       => $_abs_path . '/app/views/layouts/',
                'mail'          => $_abs_path . '/app/views/mail/',
                'libraries'     => $_abs_path . '/app/libraries/',
                'thirdparty'    => $_abs_path . '/app/thirdparty/'
            ]
        ];

        // Set the environment paths.
        static::$_env = $_data;

        // Load the application configuration file.
        static::_load_file($_data['config']['app'], 'app');

        // Load the session configuration file.
        static::_load_file($_data['config']['session'], 'session');

        // Load the database configuration file.
        static::_load_file($_data['config']['database'], 'database');

        // Load the providers configuration file.
        static::_load_file($_data['config']['providers'], 'providers');

        // Load the components configuration file.
        static::_load_file($_data['config']['components'], 'components');

        // Load the security configuration file.
        static::_load_file($_data['config']['security'], 'security');

        // Load the cache configuration file.
        static::_load_file($_data['config']['cache'], 'cache');

        // Load the mail configuration file.
        static::_load_file($_data['config']['mail'], 'mail');

        // Load the essentials environment variables.
        static::_load_base_settings();

    }

    /**
     * Load a file and store the variables
     * that are defined inside into the environment.
     *
     * @access  private
     * @param   string
     * @param   string
     * @return  void
     */
    private static function _load_file($_file, $_variable) {

        if (file_exists($_file)) {
            require_once $_file;
            $_variable = [$_variable => ${$_variable}];

            static::$_env = array_merge(static::$_env, $_variable);
        }
        else {
            Tracer::add('[[Environment:]] Error: File ' . $_file . ' not found');
            exit;
        }

    }

    /**
     * Load the essentials environment variables
     * such as php timezone for date().
     *
     * @access  private
     * @param   void
     * @return  void
     */
    private static function _load_base_settings() {

        // Set the timezone for PHP date function.
        $_timezone = static::get_env('app.timezone');

        if (!empty($_timezone)) {
            date_default_timezone_set($_timezone);
        }

        // Set the single-slash if empty base_path.
        if (empty(static::$_env['app']['base_path'])) {
            static::$_env['app']['base_path'] = '/';
        }

    }

    /**
     * Load thirdparty extension/library.
     *
     * @access  public
     * @param   string
     * @param   string
     * @return  bool
     */
    public static function load_library($_path, $_name) {

        // Generate full path for dirname() call.
        $_full_file = static::get_env('paths.libraries') . $_path . '/' . $_name . '.php';
        
        // Avoid local/remote file inclusion.
        if (dirname($_full_file) === static::get_env('paths.libraries') . $_path) {
            @include_once($_full_file);
        }

    }

    /**
     * Return the environment variable(s).
     *
     * @access  public
     * @param   string
     * @return  void
     */
    public static function get_env($_key = null) {

        if (!is_null($_key)) {

            // Find the multiple keys.
            if (strpos($_key, '.') !== false) {

                $_keys = explode('.', $_key);
                $_temp_env = static::$_env;

                // Try to find the configuration key.
                foreach ($_keys as $_value) {
                    if (isset($_temp_env[$_value])) {
                        $_temp_env = $_temp_env[$_value];
                        $_key = $_value;
                    }
                }

                // Return the matched value.
                return $_temp_env;
            }

            // Find the single key.
            if (isset(static::$_env[$_key])) {
                return static::$_env[$_key];
            }

            return '';
        }

        // Return all environment variables.
        return static::$_env;

    }

    /**
     * Set the custom application variable.
     *
     * @access  public
     * @param   string
     * @param   string
     * @return  bool
     */
    public static function set_env($_key, $_value, $_load_file = null) {

        // Retrieve environment variable.
        if (is_null($_load_file)) {
            if (static::$_env[$_key] = $_value) {
                return true;
            }
        }
        // Set a new environment variable.
        // (Return its value)
        else {
            static::_load_file(static::get_abs_path() . '/app/config/' . $_key . '.php', $_key);
            return static::$_env[$_key];
        }

    }

    /**
     * Get the absolute base path variable.
     *
     * @access  public
     * @param   void
     * @return  string
     */
    public static function get_abs_path() {

        return static::get_env('abs');

    }
    
    /**
     * Get local domain name.
     *
     * @access  public
     * @param   void
     * @return  mixed
     */
    public static function get_local_domain() {

        $_protocol = 'http' . ((isset($_SERVER['HTTPS']) and $_SERVER['HTTPS'] === 'on') ? 's' : '') . '://';
        $_host = parse_url($_protocol . $_SERVER['HTTP_HOST'], PHP_URL_HOST);
        
        preg_match("/[^\.\/]+\.[^\.\/]+$/", $_host, $_matches);

        if (!empty($_matches)) {
            return current($_matches);
        }

        return false;

    }

    /**
     * Get full locale url.
     *
     * @access  public
     * @param   void
     * @return  string
     */
    public static function get_base_url() {

        $_path_base = Environment::get_env('app.base_path');

        $_protocol = 'http' . ((isset($_SERVER['HTTPS']) and $_SERVER['HTTPS'] === 'on') ? 's' : '') . '://';
        $_url = $_protocol . $_SERVER['HTTP_HOST'] . $_path_base;

        // Remove the last slash.
        if (substr($_url, -1) == '/') {
            $_url = substr($_url, 0, -1);
        }

        return $_url;

    }

}