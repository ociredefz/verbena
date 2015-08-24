<?php

/**
 * Mail configuration file.
 */
$mail = [

    /**
     * Set the mail driver for sending mail.
     * Available drivers: smtp, mail
     */
    'driver'            => 'mail', 

    /**
     * SMTP server hostname.
     */
    'hostname'          => 'smtp.example.org',

    /**
     * SMTP server port.
     */
    'port'              => 25,

    /**
     * Set the timeout of the socket connection with
     * the SMTP server, this refers to the fsockopen() 
     * PHP function.
     */
    'timeout'           => 5,

    /**
     * Enable/disable SMTP authentication.
     */
    'auth'              => true,

    /**
     * SMTP username.
     * Leave null to disable the server authentication.
     * (needs 'authentication' to be 'true')
     */
    'username'          => 'user@example.org',

    /**
     * SMTP password.
     * (needs 'authentication' to be 'true')
     */
    'password'          => '',

    /**
     * Default mail sender (from) setting parameters.
     * This is can be changed before sending through 
     * the Mail::add_from(name, address) method.
     */
    'def_mail_address'      => 'no-reply@detectlry.com',
    'def_mail_name'         => 'Detectlry No-Reply',
    'def_mail_local_name'   => 'Detectlry Local Message',
    'def_mail_local'        => 'info@detectlry.com',

    /**
     * Set to 'true' if intend to send the mail in
     * the HTML format, otherwise 'false'.
     */
    'html'              => true,

    /**
     * Adds additional header parameters.
     *
     * Note: if 'html' is set to 'true', the parameters
     * like the 'MIME-Version' and 'Content-Type' are 
     * automatically injected, but if you want to add
     * your custom content-type and mime-version, just 
     * add below both and the module will overwrite
     * the injected parameters with yours.
     */
    'add_header_params' => [
        'X-Mailer'  => 'PHP/' . PHP_VERSION
    ]

];
