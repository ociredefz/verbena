<?php

/**
 * VERBENA FRAMEWORK
 * 2015 - MIT LICENSE.
 */

namespace Bootstrap\Dispatcher;

use Bootstrap\Environment\Tracer;
use Bootstrap\Autoloader\Autoloader;
use Bootstrap\Database\Factory;
use Bootstrap\Environment\Environment;
use Bootstrap\Components\Security;
use Bootstrap\Components\Session;
use Bootstrap\Components\HTTP;
use Bootstrap\Components\Cache;
use Bootstrap\Exceptions\DispatcherException;
use Bootstrap\Exceptions\ControllerException;

class Dispatcher {

    /**
     * Some control variables.
     * @var array
     */
    protected static $_notfound = [];
    protected static $_routes   = [];

    /**
     * Application namespaces.
     * @var array
     */
    protected static $_app_providers  = [
        'controllers'   => 'App\\Controllers\\',
        'models'        => 'App\\Models\\',
        'views'         => 'App\\Views\\'
    ];

    /**
     * Miscellanea helpers.
     * @var const
     */
    const HTTP_SEPARATOR        = '/';


    /**
     * Initialize the application's dispatcher.
     * First loads the routes directives and
     * then parses the HTTP request.
     *
     * @access  public
     * @param   void
     * @return  void
     */
    public static function start_dispatcher() {

        // Load application routes.
        static::_load_routes();

        // Parse the HTTP request.
        static::_request_parser();

    }

    /**
     * Load the routing directives.
     *
     * @access  private
     * @param   string
     * @return  void
     */
    private static function _load_routes() {

        // Load routes configuration absolute file path.
        $_file = Environment::get_env('config.routes');

        Tracer::add("[[Dispatcher:]] loading [[$_file]] configuration file");

        // Check if routes file exists.
        try {
            if (file_exists($_file)) {
                require_once $_file;
            }
            else {
                Tracer::add("[[Dispatcher:]] unable to locate [[$_file]] configuration file");
                throw new DispatcherException("unable to locate [[$_file]] configuration file");
            }
        }
        catch (DispatcherException $exception) {
            Tracer::add($exception->get_formatted_exception());
            echo $exception->get_formatted_exception();
        }

    }

    /**
     * Add a route directive.
     *
     * @access  public
     * @param   string
     * @param   string
     * @param   array
     * @return  void
     */
    public static function route($_uri, $_controller_method, $_filter = []) {

        // Check for mixed types.
        switch (gettype($_controller_method)) {
            case 'string':
                try {
                    // Controller and method have been specified.
                    if (strpos($_controller_method, '.') !== false) {
                        // Add the page not found method.
                        if ($_uri === '404') Tracer::add("[[Dispatcher:]] adding route rule for page not found (404) to [[$_controller_method]]");
                        else Tracer::add("[[Dispatcher:]] adding route rule [[$_uri]] to [[$_controller_method]]");

                        list($_controller, $_method) = explode('.', $_controller_method);
                    }
                    // No method has been specified,
                    // assume the index method as default.
                    else {
                        $_controller = $_controller_method;
                        $_method = 'index';
                    }
                }
                catch (DispatcherException $exception) {
                    Tracer::add($exception->get_formatted_exception());
                    echo $exception->get_formatted_exception();
                }
                break;
            case 'object':
                if ($_uri === '404') Tracer::add("[[Dispatcher:]] adding routing rule for page not found ([[404]]) to anonymous function");
                else Tracer::add("[[Dispatcher:]] adding route rule [[$_uri]] to anonymous function");

                $_controller = null;
                $_method = $_controller_method;
                break;
        }

        // Add the route rule for page not found.
        if ($_uri === '404') {
            return (static::$_notfound = [
                'controller'    => $_controller,
                'method'        => $_method
            ]);
        }

        // Add the rule for other pages.
        return (static::$_routes[] = [
            'uri'           => $_uri,
            'controller'    => $_controller,
            'method'        => $_method,
            'filter'        => $_filter
        ]);

    }

    /**
     * HTTP request parser.
     * Check the route associated to the
     * parsed request.
     *
     * @access  private
     * @param   void
     * @return  void
     */
    private static function _request_parser() {

        // Get uri hash location and HTTP request method.
        $_request_uri       = static::_get_request_uri();
        $_request_method    = static::_get_request_method();
        $_arguments         = [];
        $_anon_function     = false;

        if (!empty(static::$_routes)) {
            
            // Explode first controller/method of request uri if there 
            // are fewer than two value returned by explode, it append 
            // null value to the method variable.
            list($_request_uri_controller, $_request_uri_method) = 
                array_pad(explode(static::HTTP_SEPARATOR, $_request_uri), 2, null);

            // Get arguments list.
            $_exp_arguments = explode(static::HTTP_SEPARATOR, $_request_uri);

            // Store the arguments skipping controller and method.
            foreach ($_exp_arguments as $_key => $_value) {
                if ($_key and $_key >= 1) {
                    $_arguments[] = $_value;
                }
            }

            // Merge arguments if it's a POST request.
            if ($_request_method === 'POST') {
                $_arguments = array_merge($_arguments, $_POST);
            }

            foreach (static::$_routes as $_route) {
                // Check if it's an ajax request.
                if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) and !empty($_SERVER['HTTP_X_REQUESTED_WITH']) and 
                    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

                    if ($_request_method === 'POST') {
                        // Use php input to prevent empty $_POST
                        // when received a json post data.
                        // (Its generally handled by AJAX requests)
                        $json_stream = (array) json_decode(file_get_contents('php://input'));

                        // Merge POST fields. 
                        if (!empty($json_stream)) {
                            $_POST = array_merge($_POST, $json_stream);
                        }
                    }
                }

                // Standardize router uri to request uri.
                $_back_uri = $_route['uri'];
                $_route['uri'] = ltrim(rtrim($_route['uri'], 
                    static::HTTP_SEPARATOR), static::HTTP_SEPARATOR);

                // Explode first controller/method of router rule, if there 
                // are fewer than two value returned by explode, it append 
                // null value to the method variable.
                list($_route_controller, $_route_method) = 
                    array_pad(explode(static::HTTP_SEPARATOR, $_route['uri']), 2, null);

                // Compare the controller and method.
                if ($_request_uri_controller === $_route_controller) {
                    if (is_null($_route_method) and !is_null($_request_uri_method)) {
                        $_anon_function = true;
                    }
                    else {
                        if ($_request_uri_method === $_route_method) {
                            Environment::set_env('called_method', $_route_method);
                            Tracer::add("[[Dispatcher:]] founds a route rule with controller/method: [[{$_back_uri}]]");

                            // Manage the arguments list by HTTP requests.
                            // (Its generally handled by AJAX requests)
                            if ($_request_method == 'POST') {

                                // Shift only if method is set.
                                if (isset($_arguments[1])) {
                                    array_shift($_arguments);
                                }
                            }
                            // Shift for other requests.
                            else {
                                array_shift($_arguments);
                            }

                            // Create the controller instance.
                            return static::_call_class($_route, $_arguments);
                        }
                    }
                }
            }

            // Check for anonymous function.
            if ($_anon_function === true and is_object($_route['method'])) {
                return static::_call_class($_route, $_arguments);
            }

            // Restore the route rule based on
            // request uri value(s).
            $_route = [
                'controller'    => $_request_uri_controller,
                'method'        => $_request_uri_method
            ];

            $_found = null;
            $_abs_file  = Autoloader::_fs_search('bootstrap/.*', $_route['controller'] . '.php', $_found);

            // The file was found in filesystem.
            // So it returns the 404.
            if ((is_null($_found) or !$_route['method'])) {
                if (!empty(static::$_notfound)) {
                    return static::_call_class(static::$_notfound);
                }
                else {
                    Tracer::add("[[Dispatcher:]] no 404 route configured");
                    exit;
                }
            }

            // Check for real controller/method, if controller doesn't 
            // exists continue displays the page not found.
            if (static::_call_class($_route, $_arguments, false) === false) {
                // Checks if at least one route was loaded.
                Tracer::add("[[Dispatcher:]] couldn't found a valid routing rule or valid " . 
                    "controller/method for request: [[" . htmlspecialchars($_request_uri) . "]] rendering the 404 page");

                if (!empty(static::$_notfound)) {
                    return static::_call_class(static::$_notfound);
                }
                else {
                    Tracer::add("[[Dispatcher:]] no 404 route configured");
                    exit;
                }
            }
            else {
                Tracer::add("[[Dispatcher:]] no route has found, loading directly the controller/method: [[" . htmlspecialchars($_request_uri) . "]]");
            }
        }
        else {
            Tracer::add('[[Dispatcher:]] no one route has been configured in: [[' . Environment::get_env('config.routes'). ']]');
            throw new DispatcherException('no one route has been configured in: [[' . Environment::get_env('config.routes') . ']]');
        }

    }

    /**
     * Return the get request uri path.
     *
     * @access  private
     * @param   void
     * @return  string
     */
    private static function _get_request_uri() {

        // Get base URL location.
        $_base_path = Environment::get_env('app.base_path');
        $_uri = '';

        // Try to get the current URI hash path.
        if (isset($_SERVER['REQUEST_URI'])) {
            $_uri = $_SERVER['REQUEST_URI'];

            if ($request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)) {
                $_uri = $request_uri;
            }

            $_uri = rawurldecode($_uri);
        }

        // Remove the slashes.
        if ($_base_path != '/') {
            $_uri = str_replace($_base_path, '', $_uri);
        }

        // Return real hash request.
        return ltrim(rtrim(
            $_uri, 
        static::HTTP_SEPARATOR),  static::HTTP_SEPARATOR);

    }

    /**
     * Return the HTTP request method.
     * (HTTP verbs: GET, POST, PUT, DELETE, ..)
     *
     * @access  private
     * @param   void
     * @return  string
     */
    private static function _get_request_method() {

        try {
            if (isset($_SERVER['REQUEST_METHOD'])) {
                return $_SERVER['REQUEST_METHOD'];
            }
            else {
                throw new DispatcherException('could not find the HTTP request method');
            }
        }
        catch (DispatcherException $exception) {
            Tracer::add($exception->get_formatted_exception());
            echo $exception->get_formatted_exception();
        }

    }

    /**
     * Create the controller instance and
     * then call the relative method.
     *
     * @access  private
     * @param   array
     * @param   array
     * @param   bool
     * @return  void
     */
    private static function _call_class($_route, $_arguments = [], $_throw = true) {

        // Initialize database factory.
        // First check if database driver name is not empty.
        Environment::get_env('database.driver') ?
        Factory::register_handler() : null;

        // Initialize cache component.
        // First check if cache driver name is not empty.
        Environment::get_env('cache.driver') ?
        Cache::register_handler() : null;

        // Return to anonymous function.
        if (is_null($_route['controller'])) {
            return call_user_func_array($_route['method'], $_arguments);
        }
        else {
            // Set application provider namespace.
            $_namespace = static::$_app_providers['controllers'] . $_route['controller'];
            $_class = $_namespace;

            // Check existence of class name.
            if (class_exists($_class)) {

                // Try to instantiate the class.
                $_controller = $_class::instance_class();
                $_method = $_route['method'];

                // Check for router filter.
                if (isset($_route['filter']) and !is_null($_route['filter']) and !empty($_route['filter'])) {

                    // Verify authorized session field to access the controller method,
                    // if it's false redirect to custom page only if the method is not allowed.
                    if (isset($_route['filter']['auth_session']) and isset($_route['filter']['noauth_redirect'])) {
                        
                        // Requested session to proceed is not set.
                        // First check if it's a valid authentication session name.
                        if ($_route['filter']['auth_session'] !== false) {
                            if (Session::get($_route['filter']['auth_session']) === false) {

                                // Check if method is already allowed in the case
                                // the authorization session is needed.
                                if (!isset($_route['filter']['noauth_allowed'])) {
                                    return HTTP::redirect($_route['filter']['noauth_redirect']);
                                }
                            }
                            // If the requested session is set to false, it means that the
                            // page become not available for the authorized clients.
                            else {
                                if (isset($_route['filter']['noauth_allowed'])) {
                                    if ($_route['filter']['noauth_allowed'] === false) {
                                        return HTTP::redirect($_route['filter']['noauth_redirect']);
                                    }
                                }
                            }
                        }
                    }
                }

                try {
                    // Try to call the class method.
                    if (call_user_func_array([$_controller, $_method], $_arguments) === false) {
                        if ($_throw === true) {
                            throw new ControllerException("unable to instantiate the controller [[$_controller]]");
                        }
                    }
                    else {
                        return true;
                    }
                }
                catch (ControllerException $exception) {
                    Tracer::add($exception->get_formatted_exception());
                    echo $exception->get_formatted_exception();
                }
            }
            else {
                Tracer::add("[[Dispatcher:]] could not find controller {$_route['controller']}");

                if ($_throw === true) {
                    throw new ControllerException("could not find controller {$_route['controller']}");
                }
            }
        }

        return false;

    }

}