<?php

/**
 * Database configuration file.
 */
$database = [

    /**
     * PDO driver will be used.
     * Leaves empty to disable the database handler. (speed up the loader)
     *
     * Available drivers: mysql|postgresql|sqlite|mongodb
     */
    'driver'        => '',

    /**
     * MySQL parameters.
     */
    'mysql'         => [
        'hostname'      => 'localhost',
        'port'          =>  3306,
        'database'      => '',
        'username'      => '',
        'password'      => '',
        'persistent'    => true
    ],

    /**
     * PostgreSQL parameters.
     */
    'postgresql'    => [
        'hostname'      => 'localhost',
        'port'          =>  3306,
        'database'      => '',
        'username'      => '',
        'password'      => '',
        'persistent'    => true
    ],

    /**
     * SQLite parameters.
     */
    'sqlite'        => [
        'file'          => 'sqlite.sql',
        'persistent'    => true
    ],

    /**
     * NoSQL Databases.
     */

    /**
     * MongoDB parameters.
     */
    'mongodb'           => [
        'hostname'      => 'localhost',
        'port'          =>  27017,
        /**
         * For more constructor options:
         * Reference: http://php.net/manual/en/mongoclient.construct.php
         */
        'options'       => [
            'db'                => '',
            'username'          => '',
            'password'          => '',
            'connectTimeoutMS'  => -1
        ]
    ]

];