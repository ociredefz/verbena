<?php

/**
 * VERBENA FRAMEWORK
 * 2015 - MIT LICENSE.
 */

namespace Bootstrap\Environment;

use Bootstrap\Environment\Environment;
use Exception;

class Tracer {

    /**
     * Add the application log.
     * Available modes are: development, debug, production
     *
     * @access  public
     * @param   string
     * @return  const
     */
    public static function add($message) {

        $_puts = ['onfile' => false, 'onscreen' => false];

        // Check environment mode and type.
        switch (Environment::get_env('app.environment')) {
            case 'development':
                error_reporting(E_ALL);
                @ini_set('display_errors', 'on');
                
                $_puts['onfile']    = true;
                $_puts['onscreen']  = true;
                break;
            case 'debug':
                error_reporting(0);
                @ini_set('display_errors', 'off');
                
                $_puts['onfile']    = true;
                $_puts['onscreen']  = true;
                break;
            case 'production':
                error_reporting(0);
                @ini_set('display_errors', 'off');

                $_puts['onfile']    = false;
                $_puts['onscreen']  = false;
                break;
        }

        // Check for print permissions.
        if ($_puts['onfile'] === true) {
            
            // Put the message in the file log.
            if (file_put_contents(Environment::get_env('abs') . '/app/storage/app.log', $message . "\n", FILE_APPEND | LOCK_EX) === false) {
                throw new Exception('Error: unable to write to the log file');
            }

            // Put message on a video.
            // This is useful to display the stack-traces.
            if ($_puts['onscreen'] === true) {
                $message = str_replace('[[', '<strong>', $message);
                $message = str_replace(']]', '</strong>', $message);
                
                echo '<code class="debug-line">' . $message . '</code>';
            }
        }

    }

}
