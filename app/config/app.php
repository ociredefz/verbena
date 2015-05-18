<?php

/**
 * Application configuration file.
 */
$app = [

    /**
     * The environment type.
     *
     * development: set application in development-mode (display debug messages, notices and errors).
     * debug:       set application in debug-mode (display debug messages and errors).
     * quiet:       set application in quiet-mode (disable the tracer and display only errors).
     * production:  set application in production-mode (nothing).
     */
    'environment'       => 'development', 

    /**
     * The base application path.
     * Leave empty if the application is located in the webroot.
     *
     * Ex.  / (or) /sub/directory/
     * Note: Don't forget the trailing-slash.
     */
    'base_path'         => '',

    /**
     * The assets application path.
     * Note: Like the base_path, don't forget the trailing-slash.
     */
    'assets_path'       => '/assets/',

    /**
     * The default language for your application translation.
     * This is also used as fallback if session language 
     * doesn't exists.
     */
    'language'          => 'english',

    /**
     * Enable the compression of the HTML code.
     * This removes the white-spaces also in the pre/code tags.
     */
    'compress_output'   => false,

    /**
     * The default timezone for your application, which
     * will be used by the PHP date / date-time functions.
     */
    'timezone'          => 'UTC'

];
