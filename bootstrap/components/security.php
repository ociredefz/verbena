<?php

/**
 * VERBENA FRAMEWORK
 * 2015 - MIT LICENSE.
 */

namespace Bootstrap\Components;

use Bootstrap\Environment\Tracer;
use Bootstrap\Environment\Environment;
use Bootstrap\Components\Session;
use Bootstrap\Components\Language;
use Bootstrap\Views\View;
use Bootstrap\Exceptions\SecurityException;

class Security {

    /**
     * FILTER/ENCODE METHODS.
     */


    /**
     * XSS Mitigation function.
     *
     * @access  public
     * @param   string
     * @param   bool
     * @return  string
     */
    public static function filter_xss($_data = '', $_paranoid = false) {

        // Fix '&entity\n;'.
        $_data = str_replace(array('&amp;','&lt;','&gt;'), array('&amp;amp;','&amp;lt;','&amp;gt;'), $_data);
        $_data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $_data);
        $_data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $_data);
        $_data = html_entity_decode($_data, ENT_COMPAT, 'UTF-8');

        // Remove any attribute starting with 'on' or 'xmlns'.
        $_data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $_data);

        // Remove 'javascript:' and 'vbscript:' protocols.
        $_data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $_data);
        $_data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $_data);
        $_data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $_data);

        // Only works in IE '<span style="width: expression(alert('xss'));"></span>'.
        $_data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $_data);
        $_data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $_data);
        $_data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $_data);

        // Remove namespaced elements (we do not need them).
        $_data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $_data);

        do {
            // Remove really unwanted tags.
            $_old_data = $_data;
            $_data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $_data);
        }
        while ($_old_data !== $_data);

        // If paranoid mode was set, strip all tags.
        if ($_paranoid !== false) {
            $_data = strip_tags($_data);
        }

        return $_data;
    
    }

    /**
     * XSS SAFE function.
     * (OWASP Based - output skip tags)
     *
     * @access  public
     * @param   string
     * @param   string
     * @return  string
     */
    public static function encode_tags($_data = '', $_encoding = 'UTF-8') {

        return htmlspecialchars($_data, ENT_QUOTES | ENT_HTML401, $_encoding);

    }


    /**
     * CSRF METHODS.
     */


    /**
     * Inject the CSRF token in forms.
     * (OWASP Based)
     *
     * @access  public
     * @param   string
     * @return  string
     */
    public static function csrfguard_inject($_form_data) {

        $count = preg_match_all("/<form(.*?)>(.*?)<\\/form>/is", $_form_data, $_matches, PREG_SET_ORDER);
        
        if (is_array($_matches)) {
            foreach ($_matches as $_match) {

                // Don't generate csrf token for explicit declaration.
                if (strpos($_match[1], 'no-csrf') !== false) {
                    continue;
                }

                $_name = 'csrf-token-name-' . mt_rand(0, mt_getrandmax());
                $_token = static::generate_secure_token($_name);
                
                $_form_data = str_replace($_match[0], "<form{$_match[1]}>
                <input type='hidden' name='csrf-token-name' value='{$_name}' />
                <input type='hidden' name='csrf-token-value' value='{$_token}' />{$_match[2]}</form>", $_form_data);
            }
        }

        return $_form_data;

    }

    /**
     * Validate the CSRF token.
     *
     * @access  public
     * @param   void
     * @return  void
     */
    public static function csrfguard_check() {

        // Validate the CSRF token from POST data.
        if (count($_POST)) {
            if (!isset($_POST['csrf-token-name']) or !isset($_POST['csrf-token-value'])) {
                View::$errors = ['csrf-unknown' => Language::get('bootstrap.error-csrf-unknown')];
                return false;
            }
            else {
                $_name  = $_POST['csrf-token-name'];
                $_token = $_POST['csrf-token-value'];

                // Validate the token.
                if (!static::validate_csrf_token($_name, $_token)) {
                    View::$errors = ['csrf-invalid' => Language::get('bootstrap.error-csrf-invalid')];
                    return false;
                }
            }
        }

        return true;

    }

    /**
     * Generate a secure token string.
     * (OWASP Based)
     *
     * @access  public
     * @param   string
     * @return  string
     */
    public static function generate_secure_token($_name = null) {

        if (function_exists('hash_algos') and in_array('sha512', hash_algos())) {
            $_token = hash('sha512', mt_rand(0, mt_getrandmax()));
        }
        else {
            $_token = ' ';

            for ($i = 0; $i < 128; ++$i) {
                $r = mt_rand(0,35);
                
                if ($r < 26) {
                    $_chr = chr(ord('a') + $r);
                }
                else {
                    $_chr = chr(ord('0') + $r - 26);
                } 
                
                $_token .= $_chr;
            }
        }
        
        if (!is_null($_name)) {
            // Remove old unused csrf token from 
            // user session.
            Session::unset_csrf_tokens();
            
            // Set new csrf-token-name session data.
            Session::set($_name, $_token);
        }

        return $_token;

    }

    /**
     * Validate CSRF Token.
     * (OWASP Based)
     *
     * @access  public
     * @param   string
     * @param   string
     * @param   bool
     * @return  mixed
     */
    public static function validate_csrf_token($_name, $_form_token, $unset = false) {

        $_token = Session::get($_name);

        if ($_token === false) {
            return false;
        }
        elseif ($_token === $_form_token) {
            $_result = true;
        }
        else {
            $_result = false;
        }

        // Unset csrf token from session.
        // (Don't remove session csrf token is it's an AJAX request)
        if ($unset === false) {
            Session::uset($_name);
        }

        return $_result;

    }

    /**
     * Handle side CSRF check.
     *
     * @access  public
     * @param   void
     * @return  bool
     */
    public static function handle_back_csrf_check() {
        
        // Check for CSRF security token.
        if (!isset($_POST['csrf-token-name']) or !isset($_POST['csrf-token-value'])) {
            return [
                'error'     => true,
                'response'  => [
                    'title'     => Language::get('bootstrap.error-csrf-title'),
                    'message'   => Language::get('bootstrap.error-csrf-unknown')
                ]
            ];
        }
        else {
            if (Security::validate_csrf_token($_POST['csrf-token-name'], $_POST['csrf-token-value'], true) !== true) {
                return [
                    'error'     => true,
                    'response'  => [
                        'title'     => Language::get('bootstrap.error-csrf-title'),
                        'message'   => Language::get('bootstrap.error-csrf-invalid')
                    ]
                ];
            }
        }

        return true;

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

        Tracer::add("[[Security:]] called an undefined method [['$name']]");

        try {
            throw new SecurityException("called an undefined method [['$name']]");
        }
        catch (SecurityException $exception) {
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

        Tracer::add("[[Security:]] called an undefined method [['$name']]");

        try {
            throw new SecurityException("called an undefined method [['$name']]");
        }
        catch (SecurityException $exception) {
            echo $exception->get_formatted_exception();
        }
    
    }

}
