<?php

/**
 * VERBENA FRAMEWORK
 * 2015 - MIT LICENSE.
 */

namespace Bootstrap\Components;

use Bootstrap\Environment\Tracer;
use Bootstrap\Environment\Environment;
use Bootstrap\Exceptions\SecurityException;

class Security {
    
    /**
     * XSS Mitigation function.
     *
     * @access  public
     * @param   string
     * @return  string
     */
    public static function filter_xss($_data = '') {

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
