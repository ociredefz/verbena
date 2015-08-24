<?php

/**
 * VERBENA FRAMEWORK
 * 2015 - MIT LICENSE.
 */

namespace Bootstrap\Views;

use Bootstrap\Environment\Tracer;
use Bootstrap\Environment\Environment;
use Bootstrap\Components\Language;
use Bootstrap\Components\Session;
use Bootstrap\Components\Security;
use Bootstrap\Exceptions\ViewException;

class View {

    /**
     * View file extensions.
     * @var const
     */
    const FILE_EXTENSION        = '.php';
    const FILE_INC_EXTENSION    = '.inc';
    /**
     * The directive pattern.
     * @var const
     */
    const PATTERN_DIRECTIVE     = '/(\[%(.*)%])/';

    /**
     * Errors container.
     * @var array
     */
    public static $errors = [];

    /**
     * Absolute view paths.
     * @var string
     */
    protected static $_path_base;
    protected static $_path_assets;
    protected static $_path_views;
    protected static $_path_includes;
    protected static $_path_layouts;

    /**
     * Temporary buffer.
     * @var string
     */
    protected static $_buffer;


    /**
     * View renderer.
     * Read the HTML block, applies the code-blocks
     * replacements and finally displays the compound
     * web page.
     *
     * @access  public
     * @param   string
     * @param   string
     * @param   array
     * @param   bool
     * @return  void
     */
    public static function visualize($_page = 'welcome', $_layout = 'layout', $_data = [], $_return = null) {
        
        // Load environment variables.
        static::$_path_base     = Environment::get_env('app.base_path');
        static::$_path_assets   = Environment::get_env('app.assets_path');
        static::$_path_views    = Environment::get_env('paths.views');
        static::$_path_includes = Environment::get_env('paths.includes');
        static::$_path_layouts  = Environment::get_env('paths.layouts');

        try {
            // Load the layout file.
            if (($_view_layout = file_get_contents(static::$_path_layouts . $_layout . static::FILE_INC_EXTENSION)) === false) {
                throw new ViewException('unable to open view file: ' . static::$_path_layouts . $_layout . static::FILE_INC_EXTENSION);
            }
        }
        catch (ViewException $exception) {
            Tracer::add($exception->get_formatted_exception());
            echo $exception->get_formatted_exception();
        }

        try {
            // Load the web-page file.
            if (($_view = file_get_contents(static::$_path_views . $_page . static::FILE_EXTENSION)) === false) {
                throw new ViewException('unable to open view file: ' . static::$_path_views . $_page . static::FILE_EXTENSION);
            }
        }
        catch (ViewException $exception) {
            Tracer::add($exception->get_formatted_exception());
            echo $exception->get_formatted_exception();
        }

        // Replace all directives that are defined in files.
        // Available directives: include|content
        // Available providers: stylesheet|script
        static::$_buffer = $_view;

        do {
            // Directive syntax: [% directive %]
            $_view_layout = preg_replace_callback(static::PATTERN_DIRECTIVE, 'static::_replace_directives', $_view_layout);
        }
        while (preg_match(static::PATTERN_DIRECTIVE, $_view_layout));

        // Check for header response with x-powered-by.
        if (Environment::get_env('security.x-powered-by') === false) {
            if (function_exists('header_remove')) {
                header_remove('X-Powered-By');
            }
            else {
                @ini_set('expose_php', 'off');
            }
        }

        // Check for header response with x-frame-options.
        if (($_frame_option = Environment::get_env('security.x-frame-options')) !== false) {
            header('X-Frame-Options: ' . $_frame_option);
        }

        // Check for header response with content-security-policy (CSP).
        if (Environment::get_env('security.content-security-policy') === true) {
            $_csp = Environment::get_env('security.content-security-policy-allowed');

            // Generate allowed csp endpoints.
            // (Based on key/value that are not empties)
            $_allowed = '';
            foreach ($_csp as $_key => $_value) {
                if (!empty($_value)) {
                    $_allowed .= ' ' . $_key . ' ' . implode(' ', $_value) . ';';
                }
            }

            // Supported: FF 23+, Chrome 25+, Safari 7+ and Opera 19+.
            header("Content-Security-Policy:" . $_allowed);
            // Supported: IE 10+.
            header("X-Content-Security-Policy:" . $_allowed);
        }

        // Add the http strict-transport-security (HSTS).
        if (Environment::get_env('security.http-strict-transport-security') === true) {
            $_check_https = !empty($_SERVER['HTTPS']) and strtolower($_SERVER['HTTPS']) !== 'off';
            
            if ($_check_https) {
                // Supported: FF 4, Chrome 4.0.211 and Opera 12.
                // Remember it for 1 year.
                header('Strict-Transport-Security: max-age=31536000');
            }
        }

        // Check for header response with cross-origin resource sharing (CORS).
        if (($_cors = Environment::get_env('security.cross-origin-resource-sharing')) !== false) {
            foreach ($_cors as $_key => $_value) {
                if (!empty($_value)) {
                    switch ($_key) {
                        case 'access-control-allow-origin':
                            $_field = implode(', ', $_value);
                            header("Access-Control-Allow-Origin:" . $_field);
                            break;
                        case 'access-control-expose-headers':
                            $_field = implode(', ', $_value);
                            header("Access-Control-Expose-Headers:" . $_field);
                            break;
                        case 'access-control-max-age':
                            $_field = implode(' ', $_value);
                            header("Access-Control-Max-Age:" . $_field);
                            break;
                        case 'access-control-allow-credentials':
                            $_field = implode(' ', $_value);
                            header("Access-Control-Allow-Credentials:" . $_field);
                            break;
                        case 'access-control-allow-methods':
                            $_field = implode(', ', $_value);
                            header("Access-Control-Allow-Methods:" . $_field);
                            break;
                    }
                }
            }
        }

        // Check for custom headers.
        if (($_custom_headers = Environment::get_env('security.custom-headers')) !== false) {
            if (is_array($_custom_headers) and !empty($_custom_headers)) {
                foreach ($_custom_headers as $_key => $_value) {
                    header($_key . ':' . $_value);
                }
            }
        }

        // Set error control variable if at least 
        // one error was found and merge with existing 
        // errors that was setted from the controller.
        if (!isset($_data['errors'])) {
            $_data['errors'] = [];
        }
        
        $_data['errors'] = array_merge($_data['errors'], static::$errors);
        
        // Turn on output buffering. extract the variables
        // from the array, then evaluates the html code 
        // (and nested php if exists), return the buffered
        // output and finally turn off output buffering.
        ob_start();

        extract($_data);
        echo eval('?>' . preg_replace("/;*\s*\?>/", "; ?>", str_replace('<?!', '<?php echo ', $_view_layout)));

        $_content = ob_get_contents();

        ob_end_clean();

        // Inject the CSRF token to all forms.
        $_content = Security::csrfguard_inject($_content);
        
        // Return or render the evaluated code.
        if (!is_null($_return)) {
            return $_content;
        }

        // Check for compress-output setting before echo data.
        if (Environment::get_env('app.compress_output') === true) {
            echo static::_compress_data($_content);
        }
        else {
            echo $_content;
        }

    }

    /**
     * A replacer for [% directive %].
     *
     * @access  private
     * @param   array
     * @return  array
     */
    private static function _replace_directives($_matches) {

        $_matches[2] = trim($_matches[2]);

        // Single directive.
        if (strpos($_matches[2], ' ') === false) {
            $_directive = $_matches[2];
        }
        // Compound directive.
        else {
            list($_directive, $_sub) = explode(' ', $_matches[2]);
        }

        // The assets path.
        $_path_assets = str_replace('/', '', static::$_path_assets);

        // Check the directive.
        switch ($_directive) {

            // Replace the base directives.
            // Here is the list of base directives.
            case 'include':
                try {
                    // Load the includes.
                    if (($_matches[2] = file_get_contents(static::$_path_includes . $_sub . static::FILE_INC_EXTENSION)) === false) {
                        throw new ViewException('unable to open view file: ' . static::$_path_includes . $_sub . static::FILE_INC_EXTENSION);
                    }
                }
                catch (ViewException $exception) {
                    Tracer::add($exception->get_formatted_exception());
                    echo $exception->get_formatted_exception();
                }
                break;
            case 'content':
                // Load the page content.
                $_matches[2] = static::$_buffer;
                break;
            case 'stylesheet':
                // Load the stylesheet.
                $_matches[2] = '<link rel="stylesheet" href="' . static::$_path_base . $_path_assets . '/stylesheets/' . $_sub . '.css?' . time() . '">';
                break;
            case 'javascript':
                // Load the script.
                $_matches[2] = '<script src="' . static::$_path_base . $_path_assets . '/javascripts/' . $_sub . '.js?' . time() . '"></script>';
                break;

            // Replace the scripts/stylesheets providers.
            case 'provider-script':
            case 'provider-style':
                // Check if provider exists in the providers list.
                $_provider = Environment::get_env('providers.' . str_replace('-', '_', $_directive) . 's.' . $_sub);

                // Check the type of provider.
                if (!is_array($_provider)) {
                    if ($_directive == 'provider-script') {
                        $_matches[2] = '<script src="' . $_provider . '"></script>';
                    }
                    else {
                        $_matches[2] = '<link rel="stylesheet" href="' . $_provider . '.css">';
                    }
                }
                else {
                    $_matches[2] = '';
                }
                break;
        }

        // Return the replacement.
        return $_matches[2];

    }

    /**
     * Compress the HTML code before
     * rendering.
     *
     * @access  private
     * @param   string
     * @return  string
     */
    private static function _compress_data($_code) {

        // Preserve pre/code tags.
        preg_match_all('!(<(?:code|pre).*>[^<]+</(?:code|pre)>)!',$_code, $_pre);

        // Remove pre/code tags.
        $_code = preg_replace('!<(?:code|pre).*>[^<]+</(?:code|pre)>!', '#pre#', $_code);
        
        // Remove unuseful comments.
        $_code = preg_replace('#<!--[^\[].+-->#', '', $_code);
        
        // Remove new lines, spaces and tabs.
        $_code = preg_replace('/[\r\n\t]+/', ' ', $_code);
        $_code = preg_replace('/>[\s]+</', '><', $_code);
        $_code = preg_replace('/[\s]+/', ' ', $_code);

        // Restore pre/code tags.
        if (!empty($_pre[0])) {
            foreach ($_pre[0] as $_tag) {
                $_code = preg_replace('!#pre#!', $_tag, $_code,1);
            }
        }

        return $_code;

    }

    /**
     * Redirect to location.
     *
     * @access  public
     * @param   string
     * @return  function
     */
    public static function redirect($_location = null) {

        // Get base path.
        $_path_base = Environment::get_env('app.base_path');

        // Redirect to location.
        if (is_null($_location)) {
            header('Location: ' . $_path_base);
        }
        else {
            header('Location: ' . $_path_base . $_location);
        }

    }

    /**
     * Magic method that will be called when trying
     * to call a class method that doesn't exists.
     *
     * @access  public
     * @param   string
     * @param   array
     * @return  exception
     */
    public function __call($name, $arguments) {
    
        Tracer::add("[[View:]] called an undefined method [['$name']]");

        try {
            throw new ViewException("called an undefined method [['$name']]");
        }
        catch (ViewException $exception) {
            echo $exception->get_formatted_exception();
        }

    }

    /**
     * Magic method that will be called when trying
     * to call statically a class method that 
     * doesn't exists.
     *
     * @access  public
     * @param   string
     * @param   array
     * @return  exception
     */
    public static function __callStatic($name, $arguments) {

        Tracer::add("[[View:]] called an undefined method [['$name']]");

        try {
            throw new ViewException("called an undefined method [['$name']]");
        }
        catch (ViewException $exception) {
            echo $exception->get_formatted_exception();
        }
    
    }

}