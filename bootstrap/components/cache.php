<?php

/**
 * VERBENA FRAMEWORK
 * 2015 - MIT LICENSE.
 */

namespace Bootstrap\Components;

use Memcache;
use Redis;
use Bootstrap\Environment\Environment;
use Bootstrap\Environment\Tracer;
use Bootstrap\Exceptions\CacheException;

class Cache {

    /**
     * Environment configuration and 
     * error control variables.
     * @var array
     */
    protected static $_env_cache = [];

    /**
     * Cache driver instance.
     * @var object
     */
    protected static $_driver;

    /**
     * Cache driver instance.
     * (for direct external calls)
     * @var object
     */
    public static $handler;


    /**
     * Create the cache instance.
     *
     * @access  public
     * @param   void
     * @return  function
     */
    public static function register_handler() {

        try {
            // Retrieve cache environment variables.
            static::$_env_cache = Environment::get_env('cache');

            // Check cache engine driver to be used.
            switch (static::$_env_cache['driver']) {

                // Memcache cache server.
                case 'memcache':

                    // Instance the memcache driver.
                    $_memcache = new Memcache();

                    // Retrieve cache server data.
                    $_hostname = static::$_env_cache['hostname'];
                    $_port = static::$_env_cache['port'];
                    $_persistent = static::$_env_cache['persistent'];

                    // Add cache server.
                    $_memcache->addServer($_hostname, $_port, $_persistent);

                    // Set cache driver instance.
                    static::$handler = static::$_driver = $_memcache;

                    break;

                // Redis cache server.
                case 'redis':

                    // Retrieve cache server data.
                    $_hostname = static::$_env_cache['hostname'];
                    $_port = static::$_env_cache['port'];
                    $_persistent = static::$_env_cache['persistent'];

                    // Instance the redis driver.
                    $_redis = new Redis();

                    // Connect to a cache server.
                    if ($_redis) {

                        // Use persistent connection.
                        if ($_persistent) {
                            $_redis->pconnect($_hostname, $_port);
                        }
                        // No-persistent connection.
                        else {
                            $_redis->connect($_hostname, $_port);
                        }

                        // Set cache driver instance.
                        static::$handler = static::$_driver = $_redis;
                    }

                    break;
            }
        }
        catch(CacheException $exception) {
            throw new CacheException($exception->get_formatted_exception());
        }
    
    }

    /**
     * Sets a new cache value only if it 
     * isn't already set.
     *
     * @access  public
     * @param   string
     * @param   string
     * @param   string
     * @return  function
     */
    public static function add($key, $value) {

        return static::$_driver->add($key, $value);

    }

    /**
     * Replaces a Value already set.
     *
     * @access  public
     * @param   string
     * @param   string
     * @param   string
     * @return  function
     */
    public static function replace($key, $value) {

        return static::$_driver->replace($key, $value);

    }

    /**
     * Sets a new cache value whether or
     * not key is set.
     *
     * @access  public
     * @param   string
     * @param   string
     * @param   string
     * @return  function
     */
    public static function set($key, $value) {

        return static::$_driver->set($key, $value);

    }

    /**
     * Gets cache value of specified key.
     *
     * @access  public
     * @param   string
     * @return  function
     */
    public static function get($key) {

        return static::$_driver->get($key);

    }

    /**
     * Increments a stored integer by specified
     * increment rate.
     *
     * @access  public
     * @param   void
     * @return  function
     */
    public static function increment($key, $num = 1) {

        return static::$_driver->increment($key, $num);

    }

    /**
     * Decrement a stored integer by specified
     * decrement rate.
     *
     * @access  public
     * @param   string
     * @param   integer
     * @return  function
     */
    public static function decrement($key, $num = 1) {

        return static::$_driver->decrement($key, $num);

    }

    /**
     * Delete a specified key from the cache.
     *
     * @access  public
     * @param   void
     * @return  function
     */
    public static function delete($key) {

        return static::$_driver->delete($key, 0);

    }

    /**
     * Removes every record from the cache.
     *
     * @access  public
     * @param   void
     * @return  function
     */
    public static function flush() {

        return static::$_driver->flush();

    }

    /**
     * Returns an array with information
     * about the cache.
     *
     * @access  public
     * @param   void
     * @return  function
     */
    public static function get_stats() {

        return static::$_driver->getStats();

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

        Tracer::add("[[Cache:]] called an undefined method [['$name']]");

        try {
            throw new CacheException("called an undefined method [['$name']]");
        }
        catch (CacheException $exception) {
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

        Tracer::add("[[Cache:]] called an undefined method [['$name']]");

        try {
            throw new CacheException("called an undefined method [['$name']]");
        }
        catch (CacheException $exception) {
            echo $exception->get_formatted_exception();
        }
    
    }

}
