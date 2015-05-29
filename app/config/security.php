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
     *      https://developer.mozilla.org/en-US/docs/Web/HTTP/Access_control_CORS
     *
     * X-Frame-Options:                     setting this option, tells the browser that do not allows 
     *                                      the other sites to display your page inside an iframe.
     *                                      This is a protection against the Clickjacking attacks.
     *                                      Set to 'false' to disable in http response, or specific 
     *                                      the frame option.
     * X-Powered-By:                        disable or enable the X-Powered-By in header response,
     *                                      this is useful to prevent gathering information about
     *                                      current php version.
     *                                      Set to 'false' to disable in http reponse.
     * Content-Security-Policy:             (CSP) with this, you can specify from which locations 
     *                                      you accept javascript, which sites are allowed to 
     *                                      show your page inside an iframe and many other things.
     *                                      If a browser supports CSP, this can be an effective 
     *                                      protection against Cross-Site-Scripting.
     * HTTP Strict-Transport-Security:      (HSTS) with this, the first time a user visits your 
     *                                      site, the browser will store this header. If the user
     *                                      later visits your site again, maybe using an unsafe
     *                                      WLAN connection, the browser remembers to call it
     *                                      exclusively with HTTPS. This would then protect from 
     *                                      SSL-strip.
     * Access-Control-Allow-Origin          The Access-Control-Allow-Origin header indicates 
     *                                      whether a resource can be shared based by returning 
     *                                      the value of the Origin request header, "*", or "null" 
     *                                      in the response.
     * Access-Control-Allow-Credentials:    The Access-Control-Allow-Credentials header indicates 
     *                                      whether the response to request can be exposed when 
     *                                      the omit credentials flag is unset. When part of the 
     *                                      responseto a preflight request it indicates that the 
     *                                      actual request can include user credentials.
     * Access-Control-Expose-Headers:       The Access-Control-Expose-Headers header indicates 
     *                                      which headers are safe to expose to the API of a CORS 
     *                                      API specification.
     * Access-Control-Max-Age:              The Access-Control-Max-Age header indicates how long 
     *                                      the results of a preflight request can be cached in a 
     *                                      preflight result cache.
     * Access-Control-Allow-Methods:        The Access-Control-Allow-Methods header indicates, as 
     *                                      part of the response to a preflight request, which 
     *                                      methods can be used during the actual request.
     * Access-Control-Allow-Headers:        The Access-Control-Allow-Headers header indicates, as 
     *                                      part of the response to a preflight request, which 
     *                                      header field names can be used during the actual request.
     */
    'x-frame-options'                           => 'SAMEORIGIN',
    'x-powered-by'                              => false,
    'content-security-policy'                   => true,
    'content-security-policy-allowed'           => [
        'default-src'                       => [],
        'script-src'                        => [
            'verbena.local',
            'verbena.deftcode.ninja',
            "'unsafe-inline'",
            "'unsafe-eval'"
        ],
        'object-src'                        => [],
        'style-src'                         => [
            'verbena.local',
            'verbena.deftcode.ninja',
            'fonts.googleapis.com',
            "'unsafe-inline'"
        ],
        'img-src'                           => [],
        'media-src'                         => [],
        'frame-src'                         => [],
        'font-src'                          => [
            'verbena.local',
            'verbena.deftcode.ninja',
            'fonts.gstatic.com',
            'fonts.googleapis.com'
        ],
        'connect-src'                       => [],
        'form-action'                       => [],
        'sandbox'                           => [],
        'script-nonce'                      => [],
        'plugin-types'                      => [],
        'reflect-xss'                       => [],
        'report-uri'                        => []
    ],
    'http-strict-transport-security'            => false,
    'cross-origin-resource-sharing'             => [
        'access-control-allow-origin'       => [
            'http://verbena.deftcode.ninja'
        ],
        'access-control-expose-headers'     => [
            'X-VERBENA'
        ],
        'access-control-max-age'            => [
            86400
        ],
        'access-control-allow-credentials'  => [
            'true'
        ],
        'access-control-allow-methods'      => [
            'GET', 'POST'
        ]
    ],
    'custom-headers'                            => [
        'X-VERBENA'                         => '2015.1',
    ]

];