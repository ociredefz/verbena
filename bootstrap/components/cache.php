<?php

/**
 * VERBENA FRAMEWORK
 * 2015 - MIT LICENSE.
 */

namespace Bootstrap\Components;

use Memcache;
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
    protected static $_cache_driver;


    /**
     * Constructor.
     *
     * @access  public
     * @param   void
     * @return  function
     */
    public function __construct() {

        try {
            // Retrieve cache environment variables.
            static::$_env_cache = Environment::get_env('cache');

            // Check cache engine driver to be used.
            switch (static::$_env_cache['driver']) {
                case 'memcache':
                    // Instance the memcache driver.
                    $_memcache = new Memcache();

                    // Retrieve cache server data.
                    $_hostname = static::$_env_cache['hostname'];
                    $_port = static::$_env_cache['port'];

                    // Add cache server.
                    $_memcache->addServer($_hostname, $_port);
                    
                    // Set cache driver instance.
                    static::$_cache_driver = $_memcache;
                    break;
            }
        }
        catch(CacheException $exception) {
            throw new CacheException($exception->get_formatted_exception());
        }
    
    }

    /**
     * Destructor.
     *
     * @access  public
     * @param   void
     * @return  function
     */
    public function __destruct() {

        static::$_cache_driver->close();
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
    public static function cache_add($key, $value, $expire) {

        return static::$_cache_driver->add($key, $value, false, $expire);

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
    public static function cache_replace($key, $value, $expire) {

        return static::$_cache_driver->replace($key, $value, false, $expire);

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
    public static function cache_set($key, $value, $expire) {

        return static::$_cache_driver->set($key, $value, false, $expire);

    }

    /**
     * Gets cache value of specified key.
     *
     * @access  public
     * @param   string
     * @return  function
     */
    public static function cache_get($key) {

        return static::$_cache_driver->get($key);

    }

    /**
     * Increments a stored integer by specified
     * increment rate.
     *
     * @access  public
     * @param   void
     * @return  function
     */
    public static function cache_increment($key, $num=1) {

        return static::$_cache_driver->increment($key, $num);

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
    public static function cache_decrement($key, $num = 1) {

        return static::$_cache_driver->decrement($key, $num);

    }

    /**
     * Delete a specified key from the cache.
     *
     * @access  public
     * @param   void
     * @return  function
     */
    public static function cache_delete($key) {

        return static::$_cache_driver->delete($key, 0);

    }

    /**
     * Removes every record from the cache.
     *
     * @access  public
     * @param   void
     * @return  function
     */
    public static function cache_flush() {

        return static::$_cache_driver->flush();

    }

    /**
     * Returns an array with information
     * about the cache.
     *
     * @access  public
     * @param   void
     * @return  function
     */
    public static function cache_get_stats() {

        return static::$_cache_driver->getStats();

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
