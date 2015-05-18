<?php

/**
 * VERBENA FRAMEWORK
 * 2015 - MIT LICENSE.
 */

namespace Bootstrap\Components;

use Bootstrap\Environment\Tracer;
use Bootstrap\Environment\Environment;
use Bootstrap\Exceptions\MailException;

class Mail {

    /**
     * Available integreated drivers.
     */
    protected static $_drivers  = [
        'smtp', 'mail'
    ];

    /**
     * Environment configuration and 
     * error control variables.
     */
    protected static $_env_mail = [];
    protected static $_error    = '';

    /**
     * Server related parameters.
     */
    public static $hostname     = null;
    public static $port         = null;
    public static $timeout      = null;
    public static $username     = null;
    public static $password     = null;

    /**
     * Sender/Recipient parameters.
     */
    public static $from_address = null;
    public static $from_name    = null;
    public static $recipients   = [];

    /**
     * Message parameters.
     */
    public static $subject      = null;
    public static $message      = null;

    /**
     * CRLF carriage return, newline.
     */
    const CRLF                  = "\r\n";
    const BUFFER_SIZE           = 512;


    /**
     * Add sender name and address.
     *
     * @access  public
     * @param   string
     * @param   string
     * @return  void
     */
    public static function add_from($_from_name = null, $_from_address = null) {

        // Check for non-empty data and
        // valid mail address.
        if (is_null($_from_name) or is_null($_from_address) or
            empty($_from_name) or empty($_from_address) or
            !filter_var($_from_address, FILTER_VALIDATE_EMAIL)) {
            
            static::$_error = 'Error: Invalid sender name or mail address.';
            return false;
        }

        static::$from_name = $_from_name;
        static::$from_address = $_from_address;

    }

    /**
     * Add the recipient(s).
     *
     * @access  public
     * @param   array
     * @return  bool
     */
    public static function add_recipients($_recipients = []) {

        // Check for valid data.
        if (is_array($_recipients) and !empty($_recipients)) {
            foreach ($_recipients as $_key => $_value) {

                // Add to recipients list only the
                // valid mail addresses.
                if (filter_var($_value, FILTER_VALIDATE_EMAIL)) {
                    static::$recipients[] = $_value;
                }
                // Empty the recipient list and return
                // an error instead of continue.
                else {
                    static::$recipients = [];
                    static::$_error = 'Error: One of your recipient addresses is invalid.';
                    return false;
                }
            }
        }
        // Perform same checks as above.
        else {
            if (filter_var($_recipients, FILTER_VALIDATE_EMAIL)) {
                static::$recipients[] = $_recipients;
            }
            else {
                static::$recipients = [];
                static::$_error = 'Error: Invalid recipient mail address.';
                return false;
            }
        }

    }

    /**
     * Add the subject.
     *
     * @access  public
     * @param   string
     * @return  void
     */
    public static function add_subject($_subject = null) {

        // Check for valid data.
        if (is_null($_subject) or empty($_subject)) {
            static::$_error = 'Error: Invalid message subject.';
            return false;
        }
        
        static::$subject = $_subject;

    }

    /**
     * Add the message.
     *
     * @access  public
     * @param   string
     * @return  void
     */
    public static function add_message($_message = null) {

        // Check for valid data.
        if (is_null($_message) or empty($_message)) {
            static::$_error = 'Error: Invalid message body.';
            return false;
        }
        
        // Wordwrap of the message, if any of 
        // lines is larger than 70 characters.
        static::$message = wordwrap($_message, 70, static::CRLF);

    }

    /**
     * Get the error code/message.
     *
     * @access  public
     * @param   void
     * @return  array
     */
    public static function get_error() {

        return static::$_error;

    }

    /**
     * The sendmail process method.
     *
     * @access  public
     * @param   void
     * @return  object
     */
    public static function send() {

        // Get the environment mail variables.      
        static::$_env_mail = Environment::get_env('mail');

        // Validate mail parameters.
        static::_validate_parameters();

        // Check if selected driver is supported.
        try {
            if (!in_array(static::$_env_mail['driver'], static::$_drivers)) {
                    Tracer::add('[[Mail:]] driver [[' . static::$_env_mail['driver'] . ']] is not supported');
                    throw new MailException('the driver [[' . static::$_env_mail['driver'] . ']] is not supported');
            }
        }
        catch (MailException $exception) {
            echo $exception->get_formatted_exception();
            return false;
        }

        // Set the sender data from the environment setting
        // (if no Mail::add_from() method has been call).
        if (is_null(static::$from_address) or is_null(static::$from_name)) {
            static::$from_address   = static::$_env_mail['def_mail_address'];
            static::$from_name      = static::$_env_mail['def_mail_name'];
        }

        // Add the 'MIME-Version' and 'Content-Type'
        // if the mail format is HTML.
        $_mime_version = 'MIME-Version: 1.0' . static::CRLF;
        $_content_type = 'Content-type: text/plain; charset=iso-8859-1' . static::CRLF;
        if (static::$_env_mail['html'] === true) {
            $_content_type = 'Content-type: text/html; charset=iso-8859-1' . static::CRLF;
        }

        // Add the additional header parameters to the
        // mail header (if at least one exists).
        $_add_header_params = [];
        if (!empty(static::$_env_mail['add_header_params'])) {
            foreach (static::$_env_mail['add_header_params'] as $_key => $_value) {
                $_add_header_params[] = $_key . ': ' . $_value . static::CRLF;

                // Overwrite the previously injected parameters
                // 'MIME-Version' and 'Content-Type' with the
                // parameters supplied by the user.
                if (strtolower($_key) == 'mime-version') {
                    $_mime_version = '';
                }
                elseif (strtolower($_key) == 'content-type') {
                    $_content_type = '';
                }
            }
        }

        // Use SMTP driver.
        if (static::$_env_mail['driver'] === 'smtp') {
            $_socket = fsockopen(static::$hostname, static::$port, $errno, $errstr, static::$timeout);
            
            if (($_response = static::_parse_response($_socket, '220')) === false) {
                static::$_error = '(0x01) Error: ' . $_response;
                return false;
            }

            // Say hello to SMTP server.
            fwrite($_socket, 'EHLO ' . static::$hostname . static::CRLF);

            if (($_response = static::_parse_response($_socket, '250')) !== true) {
                static::$_error = '(0x02) Error: ' . $_response;
                return false;
            }

            // Do the SMTP authentication phase.
            if (static::$_env_mail['auth'] === true) {
                fwrite($_socket, 'AUTH LOGIN' . static::CRLF);

                if (($_response = static::_parse_response($_socket, '334')) !== true) {
                    static::$_error = '(0x03) Error: ' . $_response;
                }
                else {
                    fwrite($_socket, base64_encode(static::$username) . static::CRLF);
                    static::_parse_response($_socket, '334');
                    
                    fwrite($_socket, base64_encode(static::$password) . static::CRLF);
                    if (($_response = static::_parse_response($_socket, '235')) !== true) {
                        static::$_error = '(0x04) Error: ' . $_response;
                        return false;
                    }
                }
            }

            fwrite($_socket, 'MAIL FROM:  <' . static::$from_address . '>' . static::CRLF);

            if (($_response = static::_parse_response($_socket, '250')) !== true) {
                static::$_error = '(0x05) Error: ' . $_response;
                return false;
            }

            // Add the recipients.
            $_temp_recipients = [];
            foreach (static::$recipients as $_key => $_value) {
                fwrite($_socket, 'RCPT TO: <' . $_value . '>' . static::CRLF);

                if (($_response = static::_parse_response($_socket, '250')) !== true) {
                    static::$_error = '(0x06) Error: ' . $_response;
                    return false;
                }

                $_temp_recipients[] = '<' . $_value . '>';
            }

            // Initialize the send message data.
            fwrite($_socket, 'DATA' . static::CRLF);
            if (($_response = static::_parse_response($_socket, '354')) !== true) {
                static::$_error = '(0x07) Error: ' . $_response;
                return false;
            }

            // Generate the header and sends the message.
            fwrite($_socket, $_mime_version . $_content_type .
                'To: '          . implode(', ', $_temp_recipients) . static::CRLF .
                'From: '        . static::$from_name . ' <' . static::$from_address . '>' . static::CRLF .
                'Subject: '     . static::$subject . static::CRLF
                                . implode(static::CRLF, $_add_header_params) .
                static::CRLF     . static::CRLF .
                static::$message . static::CRLF);

            // End the message.
            fwrite($_socket, '.' . static::CRLF);

            // Say the goodbye to the SMTP server.
            fwrite($_socket, 'QUIT' . static::CRLF);
            if (($_response = static::_parse_response($_socket, '250')) !== true) {
                static::$_error = '(0x08) Error: ' . $_response;
                return false;
            }

            @fclose($_socket);

            return true;
        }
        // Use PHP mail() driver.
        elseif (static::$_env_mail['driver'] === 'mail') {
            ini_set('smtp_server', static::$hostname);
            ini_set('smtp_port', static::$port);
            ini_set('auth_username', static::$username);
            ini_set('auth_password', static::$password);

            // Add the recipients.
            $_temp_recipients = '';
            foreach (static::$recipients as $_key => $_value) {
                $_temp_recipients[] = '<' . $_value . '>';
            }

            // Generate the custom header.
            $_header = 
                $_mime_version . 
                $_content_type .
                'To: '      . implode(', ', $_temp_recipients) . static::CRLF .
                'From: '    . static::$from_name . ' <' . static::$from_address . '>' . static::CRLF .
                'Subject: ' . static::$subject . static::CRLF
                            . implode(static::CRLF, $_add_header_params);

            return mail(implode(', ', $_temp_recipients), 
                    static::$subject, 
                    static::$message, 
                    $_mime_version . $_content_type);
        }

    }

    /**
     * Validate mail parameters.
     *
     * @access  private
     * @param   void
     * @return  bool
     */
    private static function _validate_parameters() {

        // Check the SMTP parameters.
        foreach (get_class_vars(get_class()) as $_key => $_value) {

            // Check the recipients list.
            if (is_array(static::${$_key}) and $_key == 'recipients') {
                try {
                    if (empty(static::${$_key})) {
                        Tracer::add('[[Mail:]] invalid [[' . $_key . ']] setting parameter');
                        throw new MailException('invalid [[' . $_key . ']] setting parameter');
                    }
                }
                catch (MailException $exception) {
                    echo $exception->get_formatted_exception();
                    return false;
                }
            }

            // Check the other parameters.
            if (is_null(static::${$_key})) {
                // Check for a value in environment array-data.
                try {
                    if (isset(static::$_env_mail[$_key])) {
                        static::${$_key} = static::$_env_mail[$_key];

                        try {
                            if (is_null(static::${$_key})) {
                                Tracer::add('[[Mail:]] invalid [[' . $_key . ']] setting parameter');
                                throw new MailException('invalid [[' . $_key . ']] setting parameter');
                            }
                        }
                        catch (MailException $exception) {
                            echo $exception->get_formatted_exception();
                            return false;
                        }
                    }
                    else {
                        Tracer::add('[[Mail:]] invalid [[' . $_key . ']] setting parameter');
                        throw new MailException('invalid [[' . $_key . ']] setting parameter');
                    }
                }
                catch (MailException $exception) {
                    echo $exception->get_formatted_exception();
                    return false;
                }
            }
        }

        return true;

    }

    /**
     * Parse the SMTP response.
     * Check for expected status in the
     * SMTP response.
     *
     * @access  private
     * @param   bool
     * @param   string
     * @return  bool
     */

    private static function _parse_response($_socket, $_expected) {

        // Get the response and then checks 
        // for the expected status code.
        $_response = '';
        while (substr($_response, 3, 1) != ' ') {
            if (!($_response = fgets($_socket, 256))) {
                return $_response;
            }
        }

        if (substr($_response, 0, 3) === $_expected) {
            return true;
        }

        return $_response;
    
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
    
        Tracer::add("[[Mail:]] called an undefined method [['$name']]");

        try {
            throw new MailException("called an undefined method [['$name']]");
        }
        catch (MailException $exception) {
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

        Tracer::add("[[Mail:]] called an undefined method [['$name']]");

        try {
            throw new MailException("called an undefined method [['$name']]");
        }
        catch (MailException $exception) {
            echo $exception->get_formatted_exception();
        }
    
    }

}