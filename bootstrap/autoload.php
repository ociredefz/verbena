<?php

/**
 * VERBENA FRAMEWORK
 * 2015 - MIT LICENSE.
 */

namespace Bootstrap;

/**
 * Load the essentials.
 */
require_once __DIR__ . '/environment/tracer.php';
require_once __DIR__ . '/environment/environment.php';
require_once __DIR__ . '/autoloader/autoloader.php';

use Bootstrap\Environment\Environment;
use Bootstrap\Autoloader\Autoloader;
use Bootstrap\Dispatcher\Dispatcher;
use Bootstrap\Components\Session;
use Bootstrap\Database\Factory;


/**
 * Check the minimum required
 * version of PHP (>= 5.4.0).
 */
Environment::requirements();

/**
 * Load application environment.
 */
Environment::create($_abs_path);

/**
 * Register the autoload class.
 */
Autoloader::register_class();

/**
 * Register the session class.
 */
Session::register_session();

/**
 * Initialize the controllers dispatcher.
 * - Load application routes
 * - Load controllers dispatcher.
 */
Dispatcher::start_dispatcher();

/**
 * Register the shutdown function.
 */
Autoloader::register_shutdown();