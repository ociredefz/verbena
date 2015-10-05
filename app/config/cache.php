<?php

/**
 * Cache engine configuration file.
 */
$cache = [

    /**
     * Set the mail driver for cache engine.
     * Leave empty to disable the database handler.
     * Available drivers: memcache (port: 11211), redis (port: 6379)
     */
    'driver'            => '', 

    /**
     * Cache server hostname.
     */
    'hostname'          => 'localhost',

    /**
     * Cache server port.
     */
    'port'              => 6379,

    /**
     * Persistent connection?
     */
    'persistent'        => true

];
