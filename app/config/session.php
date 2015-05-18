<?php

/**
 * Session configuration file.
 */
$session = [

    /**
     * Do not tell this hash to anybody.
     *
     * encryption_key:      this key is used to secure the cookie with
     *                      HMAC authentication hash. 
     */
    'encryption_key'        => '0d9c31effbcfc94288106d2acb809faa',
    'encryption_key_salt'   => '1gGfBn0u45JhIl8z4JVy7lJ2EqRnVVFV6kLR7oCoRxHnKaQpGF2tLaKa6aLnLmCp',

    /**
     * Session directives:
     *
     * Note: when the system intercept that the HTTPS is alive, 
     * the cookie automatically will be setted with the secure 
     * paramater (using the session_set_cookie_params function).
     *
     * name:                session cookie name
     * path:                cookie path (usually the root-dir '/'),
     *                      where the cookie is active in the website
     *                      directories.
     * domain:              fully cookie domain name (.example.org)
     *                      if 'false' has set, the system auto check for
     *                      the correct domain value.
     * lifetime:            the lifetime of the cookie in seconds which 
     *                      is sent to the browser.
     * expire_on_close:     if 0 the session expire when the browser gets 
     *                      closed else set 'true' to not expire.
     * secure:              use secure connection (HTTP).
     * encrypt:             enable/disable cookie encryption.
     * httponly:            set the cookie secure flag.
     * database:            use database as storage type for sessions.
     * table:               table name in database.
     */
    'name'                  => 'vn_session',
    'path'                  => '/',
    'domain'                =>  false,
    'lifetime'              =>  2678400,
    'expire_on_close'       =>  false,
    'secure'                =>  false,
    'encrypt'               =>  true,
    'httponly'              =>  true,

    // No yet implemented.
    'database'              =>  false,
    'table'                 => 'sessions'

];
