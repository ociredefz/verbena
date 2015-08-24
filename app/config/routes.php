<?php

/**
 * Routes configuration file.
 */
use Bootstrap\Dispatcher\Dispatcher;

/**
 * Routes directives.
 *
 * Examples: 
 *  /my-controller/my-method
 *  /my-controller/my-method/[my-argument[1,2,3]]
 *
 * @param   string
 * @param   mixed
 * @param   mixed
 */


// 404 Not-found router rule.
Dispatcher::route('404', 'welcome.notfound');

// Language router rules.
Dispatcher::route('/language/set/italian',  'welcome.language');
Dispatcher::route('/language/set/english',  'welcome.language');

// Application router rules.
// If no method has been specified, the framework automatically loads the index.
// Eg. 'welcome' is a shorthand of 'welcome.index'
Dispatcher::route('/', 'welcome');

// Example router rule using anonymous function with method and arguments.
Dispatcher::route('/eg-anon/with-method', 
function($arg_first = null, $arg_last = null) {

    echo 'Anonymous function (with method) with arguments: ' . $arg_first . ' : ' . $arg_last;

});

// Example router rule using anonymous function without method but with arguments.
Dispatcher::route('/eg-anon', 
function($arg_first = null, $arg_last = null) {

    echo 'Anonymous function (without method) with arguments: ' . $arg_first . ' : ' . $arg_last;

});