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
     * @var array
     */
    protected static $_drivers  = [
        'smtp', 'mail'
    ];

    /**
     * Environment configuration and 
     * error control variables.
     * @var array
     */
    protected static $_env_mail     = [];
    protected static $_errors       = [];
    protected static $_path_mail;

    /**
     * Sender/Recipient parameters.
     * @var string|array
     */
    public static $from_address = null;
    public static $from_name    = null;
    public static $recipients   = [];

    /**
     * Message parameters.
     * @var string
     */
    public static $subject      = null;
    public static $message      = null;

    /**
     * Miscellanea variables.
     * @var string|integer
     */
    const CRLF                  = "\r\n";
    const BUFFER_SIZE           = 512;
    const FILE_EXTENSION        = '.php';


    /**
     * Add sender name and address (optional).
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
            !filter_var($_from_address, FILTER_VALIDATE_EMAIL)) {
            
            static::$_errors[] = 'Error: Invalid sender name or mail address.';
        }
        else {
            static::$from_name = $_from_name;
            static::$from_address = $_from_address;
        }

        return new static;

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
                    static::$_errors[] = 'Error: One of your recipient addresses is invalid.';
                }
            }
        }
        // Perform same checks as above.
        else {
            if (strlen($_recipients) and filter_var($_recipients, FILTER_VALIDATE_EMAIL)) {
                static::$recipients[] = $_recipients;
            }
            else {
                static::$recipients = [];
                static::$_errors[] = 'Error: Invalid recipient mail address.';
            }
        }

        return new static;

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
            static::$_errors[] = 'Error: Invalid message subject.';
        }
        else {
            static::$subject = $_subject;
        }

        return new static;

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
            static::$_errors[] = 'Error: Invalid message body.';
        }
        // Wordwrap of the message, if any of 
        // lines is larger than 70 characters.
        else {
            static::$message = wordwrap($_message, 70, static::CRLF);
        }

        return new static;

    }

    /**
     * Use a template instead of custom message.
     *
     * @access  public
     * @param   string
     * @param   array
     * @return  void
     */
    public static function add_layout($_template = null, $_data = []) {

        static::$_path_mail = Environment::get_env('paths.mail');

        // Check for valid data.
        if (is_null($_template) or empty($_template)) {
            static::$_errors[] = 'Error: Invalid mail template.';
            return new static;
        }

        try {
            // Load the mail template file.
            if (($_view_layout = file_get_contents(static::$_path_mail . $_template . static::FILE_EXTENSION)) === false) {
                throw new MailException('unable to open mail template file: ' . static::$_path_mail . $_template . static::FILE_EXTENSION);
            }
        }
        catch (MailException $exception) {
            Tracer::add($exception->get_formatted_exception());
            echo $exception->get_formatted_exception();
        }

        // Turn on output buffering. extract the variables
        // from the array, then evaluates the html code 
        // (and nested php if exists), return the buffered
        // output and finally turn off output buffering.
        ob_start();

        extract($_data);
        echo eval('?>' . preg_replace("/;*\s*\?>/", "; ?>", str_replace('<?!', '<?php echo ', $_view_layout)));

        $_content = ob_get_contents();

        ob_end_clean();

        // This will override the custom message
        // (Mail::add_message()) if exists.
        static::$message = $_content;

        return new static;

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
        if (static::_validate_parameters() === false) {
            return false;
        }

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
            $_socket = fsockopen(static::$_env_mail['hostname'], static::$_env_mail['port'], $errno, $errstr, static::$_env_mail['timeout']);
            
            if (($_response = static::_parse_response($_socket, '220')) === false) {
                static::$_errors[] = '(0x01) Error: ' . $_response;
                return false;
            }

            // Say hello to SMTP server.
            fwrite($_socket, 'EHLO ' . static::$_env_mail['hostname'] . static::CRLF);

            if (($_response = static::_parse_response($_socket, '250')) !== true) {
                static::$_errors[] = '(0x02) Error: ' . $_response;
                return false;
            }

            // Do the SMTP authentication phase.
            if (static::$_env_mail['auth'] === true) {
                fwrite($_socket, 'AUTH LOGIN' . static::CRLF);

                if (($_response = static::_parse_response($_socket, '334')) !== true) {
                    static::$_errors[] = '(0x03) Error: ' . $_response;
                }
                else {
                    fwrite($_socket, base64_encode(static::$_env_mail['username']) . static::CRLF);
                    static::_parse_response($_socket, '334');
                    
                    fwrite($_socket, base64_encode(static::$_env_mail['password']) . static::CRLF);
                    if (($_response = static::_parse_response($_socket, '235')) !== true) {
                        static::$_errors[] = '(0x04) Error: ' . $_response;
                        return false;
                    }
                }
            }

            fwrite($_socket, 'MAIL FROM:  <' . static::$from_address . '>' . static::CRLF);

            if (($_response = static::_parse_response($_socket, '250')) !== true) {
                static::$_errors[] = '(0x05) Error: ' . $_response;
                return false;
            }

            // Add the recipients.
            $_temp_recipients = [];
            foreach (static::$recipients as $_key => $_value) {
                fwrite($_socket, 'RCPT TO: <' . $_value . '>' . static::CRLF);

                if (($_response = static::_parse_response($_socket, '250')) !== true) {
                    static::$_errors[] = '(0x06) Error: ' . $_response;
                    return false;
                }

                $_temp_recipients[] = '<' . $_value . '>';
            }

            // Initialize the send message data.
            fwrite($_socket, 'DATA' . static::CRLF);
            if (($_response = static::_parse_response($_socket, '354')) !== true) {
                static::$_errors[] = '(0x07) Error: ' . $_response;
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
                static::$_errors[] = '(0x08) Error: ' . $_response;
                return false;
            }

            @fclose($_socket);

            return true;
        }
        // Use PHP mail() driver.
        elseif (static::$_env_mail['driver'] === 'mail') {
            ini_set('smtp_server',   static::$_env_mail['hostname']);
            ini_set('smtp_port',     static::$_env_mail['port']);
            ini_set('auth_username', static::$_env_mail['username']);
            ini_set('auth_password', static::$_env_mail['password']);

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

        // Set the sender data from the environment setting.
        // (if no Mail::add_from() method has been call)
        if (is_null(static::$from_address) or is_null(static::$from_name)) {
            static::$from_address = static::$_env_mail['def_mail_address'];
            static::$from_name    = static::$_env_mail['def_mail_name'];
        }

        // Check for needed variables.
        if (!empty(static::$_errors) or empty(static::$recipients) or 
            is_null(static::$subject) or is_null(static::$message)) {
            return false;
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
     * Get the error code/message.
     *
     * @access  public
     * @param   void
     * @return  array
     */
    public static function errors_mail() {

        if (!empty(static::$_errors)) {
            return static::$_errors;
        }

        return [];

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