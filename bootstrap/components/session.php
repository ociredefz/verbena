<?php

/**
 * VERBENA FRAMEWORK
 * 2015 - MIT LICENSE.
 */

namespace Bootstrap\Components;

use Bootstrap\Environment\Tracer;
use Bootstrap\Environment\Environment;
use Bootstrap\Components\Encrypt;
use Bootstrap\Exceptions\SessionException;

class Session {

    protected static $_env_session  = [];
    protected static $_session_data = [];


    /**
     * Start the register the cookie session.
     *
     * @access  public
     * @param   string
     * @return  void
     */
    public static function register_session() {

        // Get the configuration variables.
        static::$_env_session = Environment::get_env('session');

        // Check for session creation or update.
        if (static::_session_read() === false) {
            Tracer::add('[[Session:]] cookie was not found, creating a new session');
            static::_session_handler('create');
        }
        else {
            Tracer::add('[[Session:]] cookie was found, updating the current session');
            static::_session_handler('update');
        }

    }

    /**
     * Read the session cookie and check for
     * HMAC signature, activity and expire.
     *
     * @access  private
     * @param   void
     * @return  bool
     */
    private static function _session_read() {

        // Check for the session cookie.
        if (!isset($_COOKIE[static::$_env_session['name']])) {
            return false;
        }

        $_session = $_COOKIE[static::$_env_session['name']];

        // Check for the sign (HMAC Authentication).
        $_len = strlen($_session) - 40;

        if (!$_len) {
            Tracer::add('[[Session:]] the cookie was not signed');
            return false;
        }

        // Check if the HMAC sign is valid.
        $_hmac = substr($_session, $_len);
        $_session = substr($_session, 0, $_len);

        // Time-attack-safe comparison.
        $_hmac_check = hash_hmac('sha1', $_session, static::$_env_session['encryption_key']);
        $_diff = 0;

        for ($_i = 0; $_i < 40; $_i++) {
            $_xor = ord($_hmac[$_i]) ^ ord($_hmac_check[$_i]);
            $_diff |= $_xor;
        }

        if ($_diff !== 0) {
            Tracer::add('[[Session:]] HMAC mismatch. Invalid cookie sign hash');
            static::_session_destroy();
            return false;
        }

        // Decrypt the session data, if encryption is enabled.
        if (static::$_env_session['encrypt'] === true) {
            $_session = Encrypt::decrypt($_session);
        }

        // Unserialize the session cookie.
        $_session = @unserialize($_session);

        // Check for the cookie expiration.
        if (($_session['last_activity'] + static::$_env_session['lifetime']) < time()) {
            Tracer::add('[[Session:]] cookie expired.');
            static::_session_destroy();
            return false;
        }

        // Set back the session parameters,
        // this is need by handling the update
        // and other client session data.
        static::$_session_data = $_session;

        return true;

    }

    /**
     * Destroy the session cookie.
     *
     * @access  private
     * @param   void
     * @return  void
     */
    private static function _session_destroy() {

        // Drop the cookie by force the
        // expire value to negative.
        setcookie(
            static::$_env_session['name'],
            serialize([]),
            (time() - 31500000),
            static::$_env_session['path'],
            static::$_env_session['domain'],
            0
        );

    }

    /**
     * Session handler, this is used for create
     * and update the client session data.
     *
     * @access  private
     * @param   string
     * @return  void
     */
    private static function _session_handler($_handle = 'create') {

        // Handle the session cookie update.
        if ($_handle === 'update') {
            // Check if session expired.
            if ((static::$_session_data['last_activity'] + static::$_env_session['lifetime']) > time()) {
                return;
            }
        }

        // Generate a new session id.
        $session_id = '';
        while (strlen($session_id) < 32) {
            $session_id .= mt_rand(0, mt_getrandmax());
        }

        // Set session header.
        static::$_session_data = [
            'session_id'    => md5(uniqid($session_id, true)),
            'last_activity' => time()
        ];

        // Set the session cookie.
        static::_set_session();

    }

    /**
     * Set the cookie session data.
     *
     * @access  private
     * @param   string
     * @param   string
     * @return  void
     */
    private static function _set_session() {

        // Serialize and signs the session data.
        $_cookie = serialize(static::$_session_data);

        // Encrypt session data, if encryption is enabled.
        if (static::$_env_session['encrypt'] === true) {
            $_cookie = Encrypt::encrypt($_cookie);
        }

        // HMAC session data (add sign).
        $_cookie .= hash_hmac('sha1', $_cookie, static::$_env_session['encryption_key']);

        // Set cookie lifetime.
        $_expire = (static::$_env_session['expire_on_close'] === true) ? 0 : time() + static::$_env_session['lifetime'];

        // Auto-check the domain value for the cookie.
        if (static::$_env_session['domain'] === false) {
            if ((static::$_env_session['domain'] = Environment::get_local_domain()) === false) {
                static::$_env_session['domain'] = '.';
            }
        }

        // Set the cookie.
        setcookie(
            static::$_env_session['name'],
            $_cookie,
            $_expire,
            static::$_env_session['path'],
            static::$_env_session['domain'],
            static::$_env_session['secure'],
            static::$_env_session['httponly']
        );

    }

    /**
     * Set the session data.
     *
     * @access  public
     * @param   string
     * @param   string
     * @return  bool
     */
    public static function set($_key = null, $_value = null) {

        if (is_null($_key) or is_null($_value)) {
            return false;
        }

        static::$_session_data[$_key] = $_value;
        static::_set_session();

        return true;

    }

    /**
     * Unset the session data.
     *
     * @access  public
     * @param   string
     * @return  bool
     */
    public static function uset($_key = null) {

        if (is_null($_key)) {
            return false;
        }

        unset(static::$_session_data[$_key]);
        static::_set_session();

        return true;

    }

    /**
     * Get the session data.
     *
     * @access  public
     * @param   string
     * @return  mixed
     */
    public static function get($_key = null) {

        if (is_null($_key) or !isset(static::$_session_data[$_key])) {
            return false;
        }

        return static::$_session_data[$_key];

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
    
        Tracer::add("[[Session:]] called an undefined method [['$name']]");

        try {
            throw new SessionException("called an undefined method [['$name']]");
        }
        catch (SessionException $exception) {
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
    
        Tracer::add("[[Session:]] called an undefined method [['$name']]");

        try {
            throw new SessionException("called an undefined method [['$name']]");
        }
        catch (SessionException $exception) {
            echo $exception->get_formatted_exception();
        }
    
    }

}
