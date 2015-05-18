<?php

/**
 * Security configuration file.
 */
$security = [

    /**
     * Header directives:
     *
     * Note: if the HSTS is set to true, this only work if the website is run through HTTPS.
     *
     * See: https://developer.mozilla.org/en-US/docs/Web/HTTP/X-Frame-Options
     *      https://developer.mozilla.org/en-US/docs/Web/Security/CSP
     *      https://developer.mozilla.org/en-US/docs/Web/Security/HTTP_strict_transport_security
     *
     * X-Frame-Options:                 setting this option, tells the browser that do not allows 
     *                                  the other sites to display your page inside an iframe.
     *                                  This is a protection against the Clickjacking attacks.
     *                                  Set to 'false' to disable in http response, or specific 
     *                                  the frame option.
     * X-Powered-By:                    disable or enable the X-Powered-By in header response,
     *                                  this is useful to prevent gathering information about
     *                                  current php version.
     *                                  Set to 'false' to disable in http reponse.
     * Content-Security-Policy:         (CSP) with this, you can specify from which locations 
     *                                  you accept javascript, which sites are allowed to 
     *                                  show your page inside an iframe and many other things.
     *                                  If a browser supports CSP, this can be an effective 
     *                                  protection against Cross-Site-Scripting.
     * HTTP Strict-Transport-Security:  (HSTS) with this, the first time a user visits your 
     *                                  site, the browser will store this header. If the user
     *                                  later visits your site again, maybe using an unsafe
     *                                  WLAN connection, the browser remembers to call it
     *                                  exclusively with HTTPS. This would then protect from 
     *                                  SSL-strip.
     */
    'x-frame-options'                   => 'SAMEORIGIN',
    'x-powered-by'                      => false,
    'content-security-policy'           => true,
    'content-security-policy-allowed'   => [
        'default-src'               => [],
        'script-src'                => [
            'verbena.local',
            'verbena.deftcode.ninja',
            "'unsafe-inline'",
            "'unsafe-eval'"
        ],
        'object-src'                => [],
        'style-src'                 => [
            'verbena.local',
            'verbena.deftcode.ninja',
            'fonts.googleapis.com',
            "'unsafe-inline'"
        ],
        'img-src'                   => [],
        'media-src'                 => [],
        'frame-src'                 => [],
        'font-src'                  => [
            'verbena.local',
            'verbena.deftcode.ninja',
            'fonts.gstatic.com',
            'fonts.googleapis.com'
        ],
        'connect-src'               => [],
        'form-action'               => [],
        'sandbox'                   => [],
        'script-nonce'              => [],
        'plugin-types'              => [],
        'reflect-xss'               => [],
        'report-uri'                => []
    ],
    'http-strict-transport-security'    => false,
    'custom-headers'                    => [
        'X-VERBENA'                 => '2015.1',
    ]

];