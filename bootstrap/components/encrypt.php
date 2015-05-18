<?php

/**
 * VERBENA FRAMEWORK
 * 2015 - MIT LICENSE.
 */

namespace Bootstrap\Components;

use Bootstrap\Environment\Tracer;
use Bootstrap\Environment\Environment;
use Bootstrap\Exceptions\EncryptException;

class Encrypt {

    /**
     * Function used to encrypt session data.
     *
     * @access  public
     * @param   string
     * @param   string
     * @return  void
     */
    public static function encrypt($_string, $_key = null) {

        $_env_session = Environment::get_env('session');

        // Custom key? no, loads default.
        if (is_null($_key)) {
            $_key = $_env_session['encryption_key'];
        }

        $_salt = $_env_session['encryption_key_salt'];
        $_key = substr(hash('sha256', $_salt . $_key . $_salt), 0, 32);

        $_iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $_iv = mcrypt_create_iv($_iv_size, MCRYPT_RAND);

        return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $_key, $_string, MCRYPT_MODE_ECB, $_iv));

    }

    /**
     * Function used to decrypt session data.
     *
     * @access  public
     * @param   string
     * @param   string
     * @return  void
     */
    public static function decrypt($_string, $_key = null) {

        $_env_session = Environment::get_env('session');

        // Custom key? no, loads default.
        if (is_null($_key)) {
            $_key = $_env_session['encryption_key'];
        }

        $_salt = $_env_session['encryption_key_salt'];
        $_key = substr(hash('sha256', $_salt . $_key . $_salt), 0, 32);
        
        $_iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $_iv = mcrypt_create_iv($_iv_size, MCRYPT_RAND);
    
        return mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $_key, base64_decode($_string), MCRYPT_MODE_ECB, $_iv);

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

        Tracer::add("[[Encrypt:]] called an undefined method [['$name']]");

        try {
            throw new EncryptException("called an undefined method [['$name']]");
        }
        catch (EncryptException $exception) {
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

        Tracer::add("[[Encrypt:]] called an undefined method [['$name']]");

        try {
            throw new EncryptException("called an undefined method [['$name']]");
        }
        catch (EncryptException $exception) {
            echo $exception->get_formatted_exception();
        }
    
    }

}
