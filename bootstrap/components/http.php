<?php

/**
 * VERBENA FRAMEWORK
 * 2015 - MIT LICENSE.
 */

namespace Bootstrap\Components;

use Bootstrap\Environment\Tracer;
use Bootstrap\Environment\Environment;
use Bootstrap\Components\Language;
use Bootstrap\Components\Security;
use Bootstrap\Exceptions\HTTPException;

class HTTP {

    /**
     * Validation error messages.
     * @var array
     */
    protected static $_errors = [];


    /**
     * Check HTTP request.
     *
     * @access  public
     * @param   string
     * @return  bool
     */
    public static function request($_type = '') {

        if (!empty($_type) and isset($_SERVER['REQUEST_METHOD'])) {
            if (strtolower($_SERVER['REQUEST_METHOD']) === strtolower($_type)) {
                return true;
            }
        }

        return false;
    
    }

    /**
     * Redirect application to uri.
     *
     * @access  public
     * @param   string
     * @return  function
     */
    public static function redirect($_src = '') {

        if (strpos($_src, 'http://') === false and strpos($_src, 'https://') === false) {
            // Load environment variables.
            $_path_base = Environment::get_env('app.base_path');

            $_src = Environment::get_base_url() . $_path_base . $_src;
        }

        header('Location: ' . $_src);
    
    }

    /**
     * Get all HTTP input fields.
     *
     * @access  public
     * @param   void
     * @return  array
     */
    public static function fields() {

        if (!empty($_POST)) {
            return $_POST;
        }

        return [];
    
    }

    /**
     * Get HTTP input field.
     *
     * @access  public
     * @param   mixed
     * @return  mixed
     */
    public static function field($_fields = '') {

        if (!empty($_fields)) {

            // Check for multiple fields.
            if (is_array($_fields) and !empty($_fields)) {
                $_return = [];

                foreach ($_POST as $_key => $_value) {

                    // Field exists.
                    if (in_array($_key, $_fields)) {
                        $_return[$_key] = $_value;
                    }
                }

                return $_return;
            }
            // Single field.
            else {
                if (isset($_POST[$_fields]) and strlen($_POST[$_fields])) {
                    return $_POST[$_fields];
                }
            }
        }

        return '';
    
    }

    /**
     * Validate HTTP fields.
     *
     * @access  public
     * @param   array
     * @param   array
     * @param   bool
     * @return  bool
     */
    public static function validate($_fields = [], $_rules = [], $_csrf_check = true) {

        // Check for CSRF token.
        if (Security::csrfguard_check() === false and $_csrf_check === true) {
            return false;
        }

        if (!empty($_fields) and !empty($_rules)) {

            // Cycle through the validation rules.
            foreach ($_rules as $_field => $_rules_list) {

                $_rules_list = explode('|', $_rules_list);

                foreach ($_rules_list as $_rule) {
                    $_formatted_error = '';

                    // Get rule and rule argument if exists.
                    $_rule_type = $_rule;
                    if (strpos($_rule, ':') !== false) {
                        list($_rule_type, $_rule_argument) = explode(':', $_rule);
                    }

                    // Validate the rule.
                    switch ($_rule_type) {
                        case 'required':
                            if (!isset($_fields[$_field]) or empty($_fields[$_field])) {
                                $_formatted_error = sprintf(Language::get('bootstrap.http-validation-required'), $_field);
                            }
                            break;
                        case 'required-valid-image':
                            // Check first if temporary email exists.
                            if (!isset($_FILES[$_field]['tmp_name'])) {
                                $_formatted_error = sprintf(Language::get('bootstrap.http-validation-image-upload'), $_field, $_rule_argument);
                            }
                            // Verify if it's a valid image.
                            else {
                                if (getimagesize($_FILES[$_field]['tmp_name']) === false or exif_imagetype($_FILES[$_field]['tmp_name']) === false) {
                                    $_formatted_error = sprintf(Language::get('bootstrap.http-validation-image-type'), $_field, $_rule_argument);
                                }
                            }
                            break;
                        case 'min-image-width':
                            if (isset($_FILES[$_field]['tmp_name'])) {
                                $image_sizes = getimagesize($_FILES[$_field]['tmp_name']);

                                if ($image_sizes !== false) {
                                    list($width, $height, $type, $attr) = $image_sizes;

                                    if ($width > $_rule_argument) {
                                        $_formatted_error = sprintf(Language::get('bootstrap.http-validation-image-max-width'), $_field, $_rule_argument);
                                    }
                                }
                                else {
                                    $_formatted_error = sprintf(Language::get('bootstrap.http-validation-image-type'), $_field, $_rule_argument);
                                }
                            }
                            break;
                        case 'max-image-height':
                            if (isset($_FILES[$_field]['tmp_name'])) {
                                $image_sizes = getimagesize($_FILES[$_field]['tmp_name']);

                                if ($image_sizes !== false) {
                                    list($width, $height, $type, $attr) = $image_sizes;

                                    if ($height > $_rule_argument) {
                                        $_formatted_error = sprintf(Language::get('bootstrap.http-validation-image-max-height'), $_field, $_rule_argument);
                                    }
                                }
                                else {
                                    $_formatted_error = sprintf(Language::get('bootstrap.http-validation-image-type'), $_field, $_rule_argument);
                                }
                            }
                            break;
                        case 'min-binary-size':
                            // Check first if temporary email exists.
                            if (!isset($_FILES[$_field]['tmp_name'])) {
                                $_formatted_error = sprintf(Language::get('bootstrap.http-validation-image-upload'), $_field, $_rule_argument);
                            }
                            // Verify the minimum binary size in MB.
                            else {
                                $filesize = filesize($_FILES[$_field]['tmp_name']);
                                $human_filesizes = static::human_filesizes($filesize);

                                if ($human_filesizes[2] < $_rule_argument) {
                                    $_formatted_error = sprintf(Language::get('bootstrap.http-validation-min-binary-size'), $_field, $_rule_argument);
                                }
                            }
                            break;
                        case 'max-binary-size':
                            // Check first if temporary email exists.
                            if (!isset($_FILES[$_field]['tmp_name'])) {
                                $_formatted_error = sprintf(Language::get('bootstrap.http-validation-image-upload'), $_field, $_rule_argument);
                            }
                            // Verify the mamixum vinary size in MB.
                            else {
                                $filesize = filesize($_FILES[$_field]['tmp_name']);
                                $human_filesizes = static::human_filesizes($filesize);

                                if ($human_filesizes[2] > $_rule_argument) {
                                    $_formatted_error = sprintf(Language::get('bootstrap.http-validation-max-binary-size'), $_field, $_rule_argument);
                                }
                            }
                            break;
                        case 'exact-length':
                            if (strlen($_fields[$_field]) != $_rule_argument) {
                                $_formatted_error = sprintf(Language::get('bootstrap.http-validation-exact-length'), $_field, $_rule_argument);
                            }
                            break;
                        case 'min-length':
                            if (strlen($_fields[$_field]) < $_rule_argument) {
                                $_formatted_error = sprintf(Language::get('bootstrap.http-validation-min-length'), $_field, $_rule_argument);
                            }
                            break;
                        case 'max-length':
                            if (strlen($_fields[$_field]) > $_rule_argument) {
                                $_formatted_error = sprintf(Language::get('bootstrap.http-validation-max-length'), $_field, $_rule_argument);
                            }
                            break;
                        case 'matches':
                            if ($_fields[$_field] != $_fields[$_rule_argument]) {
                                $_formatted_error = sprintf(Language::get('bootstrap.http-validation-matches'), $_field, $_rule_argument);
                            }
                            break;
                        case 'email':
                            if (filter_var($_fields[$_field], FILTER_VALIDATE_EMAIL) === false) {
                                $_formatted_error = sprintf(Language::get('bootstrap.http-validation-email'), $_field);
                            }
                            break;
                        case 'url':
                            if (filter_var($_fields[$_field], FILTER_VALIDATE_URL) === false or strpos($_fields[$_field], '.') === false) {
                                $_formatted_error = sprintf(Language::get('bootstrap.http-validation-url'), $_field);
                            }
                            break;
                        case 'ip':
                            if (filter_var($_fields[$_field], FILTER_VALIDATE_IP) === false) {
                                $_formatted_error = sprintf(Language::get('bootstrap.http-validation-ip'), $_field);
                            }
                            break;
                        case 'ipv4':
                            if (filter_var($_fields[$_field], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) === false) {
                                $_formatted_error = sprintf(Language::get('bootstrap.http-validation-ipv4'), $_field);
                            }
                            break;
                        case 'ipv6':
                            if (filter_var($_fields[$_field], FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) === false) {
                                $_formatted_error = sprintf(Language::get('bootstrap.http-validation-ipv6'), $_field);
                            }
                            break;
                        case 'domain':
                            // Check for IDN domain.
                            // Example: convert "JP納豆.例.jp" to "xn--jp-cd2fp15c.xn--fsq.jp"
                            if (strpos($_fields[$_field], 'xn--') !== FALSE) {
                                $_fields[$_field] = idn_to_ascii(urldecode($_fields[$_field]));
                            }

                            $_validate = (preg_match("/^([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))*$/i", $_fields[$_field]) // Valid chars check.
                                and preg_match("/^.{1,253}$/", $_fields[$_field])                                               // Overall length check.
                                and preg_match("/^[^\.]{1,63}(\.[^\.]{1,63})*$/", $_fields[$_field]));                          // Length of each label.

                            // Validate the domain also with the ip address validation.
                            if ($_validate != 1 or filter_var(gethostbyname($_fields[$_field]), FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) === false) {
                                $_formatted_error = sprintf(Language::get('bootstrap.http-validation-domain'), $_field);
                            }
                            break;
                        case 'alpha':
                            if (ctype_alpha($_fields[$_field]) === false) {
                                $_formatted_error = sprintf(Language::get('bootstrap.http-validation-alpha'), $_field);
                            }
                            break;
                        case 'alpha-numeric':
                            if (ctype_alnum($_fields[$_field]) === false) {
                                $_formatted_error = sprintf(Language::get('bootstrap.http-validation-alpha-numeric'), $_field);
                            }
                            break;
                        case 'dash':
                            // Extended allowed chracters, dash and underscore.
                            $valid = ['-', '_'];

                            if (ctype_alnum(str_replace($valid, '', $_fields[$_field])) === false) {
                                $_formatted_error = sprintf(Language::get('bootstrap.http-validation-dash'), $_field);
                            }
                            break;
                        case 'numeric':
                            if (ctype_digit($_fields[$_field]) === false) {
                                $_formatted_error = sprintf(Language::get('bootstrap.http-validation-numeric'), $_field);
                            }
                            break;
                        case 'is-numeric':
                            if (is_numeric($_fields[$_field]) === false) {
                                $_formatted_error = sprintf(Language::get('bootstrap.http-validation-is-numeric'), $_field);
                            }
                            break;
                        case 'integer':
                            if (is_int($_fields[$_field]) === false) {
                                $_formatted_error = sprintf(Language::get('bootstrap.http-validation-integer'), $_field);
                            }
                            break;
                        case 'decimal':
                            if (is_float($_fields[$_field]) === false) {
                                $_formatted_error = sprintf(Language::get('bootstrap.http-validation-decimal'), $_field);
                            }
                            break;
                        case 'regex-match':
                            if (preg_match($_fields[$_field], null) === false) {
                                $_formatted_error = sprintf(Language::get('bootstrap.http-validation-regex-match'), $_field);
                            }
                            break;
                        case 'less-than':
                            if ($_fields[$_field] >= $_rule_argument) {
                                $_formatted_error = sprintf(Language::get('bootstrap.http-validation-less-than'), $_field, $_rule_argument);
                            }
                            break;
                        case 'greater-than':
                            if ($_fields[$_field] <= $_rule_argument) {
                                $_formatted_error = sprintf(Language::get('bootstrap.http-validation-greater-than'), $_field, $_rule_argument);
                            }
                            break;
                    }

                    if (strlen($_formatted_error)) {
                        static::$_errors[$_field] = $_formatted_error;
                    }
                }
            }
        }
        else {
            static::$_errors['invalid-fields-rules'] = Language::get('bootstrap.invalid-fields-rules');
        }

        // No one error was found, return success.
        if (empty(static::$_errors)) {
            return true;
        }

        // At least one error was found, return failed.
        return false;

    }

    /**
     * Get validation error messages.
     *
     * @access  public
     * @param   void
     * @return  array
     */
    public static function errors_validator() {

        if (!empty(static::$_errors)) {
            return static::$_errors;
        }

        return [];

    }

    /**
     * Get human-readable filesizes.
     *
     * @access  public
     * @param   integer
     * @return  array
     */
    public static function human_filesizes($bytes) {

        $bytes = floatval($bytes);

        $arr_bytes = [
            0 => ['unit' => 'TB', 'value' => pow(1024, 4)],
            1 => ['unit' => 'GB', 'value' => pow(1024, 3)],
            2 => ['unit' => 'MB', 'value' => pow(1024, 2)],
            3 => ['unit' => 'KB', 'value' => 1024],
            4 => ['unit' => 'B', 'value' => 1],
        ];

        $result = '';

        foreach ($arr_bytes as $arr_item) {
            if ($bytes >= $arr_item['value']) {
                $result = $bytes / $arr_item['value'];
                $result = str_replace('.', ',', strval(round($result, 2))) . ' ' . $arr_item['unit'];
                break;
            }
        }

        return $result;

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

        Tracer::add("[[HTTP:]] called an undefined method [['$name']]");

        try {
            throw new HTTPException("called an undefined method [['$name']]");
        }
        catch (HTTPException $exception) {
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

        Tracer::add("[[HTTP:]] called an undefined method [['$name']]");

        try {
            throw new HTTPException("called an undefined method [['$name']]");
        }
        catch (HTTPException $exception) {
            echo $exception->get_formatted_exception();
        }
    
    }

}
