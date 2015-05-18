<?php

/**
 * Routes configuration file.
 */
use Bootstrap\Dispatcher\Dispatcher;

/**
 * Routes directives.
 *
 * Examples: 
 *      /my-controller/my-method
 *      /my-controller/my-method/[my-argument[1,2,3]]
 *
 * HTTP Request Methods:
 *      HTTP_REQUEST_[GET,POST,PUT,DELETE,AJAX]
 *
 * @param   string
 * @param   mixed
 * @param   mixed
 * @param   const
 */


// 404 Not-found router rule.
Dispatcher::route('404', 'welcome.notfound');

// Language router rules.
Dispatcher::route('/language/set/italian',  'welcome.language', null, Dispatcher::HTTP_REQUEST_GET);
Dispatcher::route('/language/set/english',  'welcome.language', null, Dispatcher::HTTP_REQUEST_GET);

// Application router rules.
// If no method has been specified, the framework automatically loads the index.
// Eg. 'welcome' is a shorthand of 'welcome.index'
Dispatcher::route('/', 'welcome', null, Dispatcher::HTTP_REQUEST_GET);

// Example router rule using anonymous function with method and arguments.
Dispatcher::route('/eg-anon/with-method', 
    function($arg_first = null, $arg_last = null) {

        echo 'Anonymous function (with method) with arguments: ' . $arg_first . ' : ' . $arg_last;

    }, null, 
Dispatcher::HTTP_REQUEST_GET);

// Example router rule using anonymous function without method but with arguments.
Dispatcher::route('/eg-anon', 
    function($arg_first = null, $arg_last = null) {

        echo 'Anonymous function (without method) with arguments: ' . $arg_first . ' : ' . $arg_last;

    }, null, 
Dispatcher::HTTP_REQUEST_GET);