<?php

/**
 * VERBENA FRAMEWORK
 * 2015 - MIT LICENSE.
 */

define('APP_PID_FILE', 'app/storage/verbera.run');

class V_Interface {

    protected static $_argv;

    /**
     * Default bind arguments.
     */
    protected static $_address  = 'localhost';
    protected static $_port     = 8000;


    /**
     * Constructor.
     *
     * @access  public
     * @param   array
     * @return  void
     */
    public function __construct($argv) {

        // Stores the arguments.
        static::$_argv = $argv;

    }

    /**
     * Interface the command-line.
     * Check for possible errors and debug mode.
     *
     * @access  public
     * @param   void
     * @return  function
     */
    public function interface_CLI() {

        // Remove last pid file.
        @unlink(APP_PID_FILE);

        // Display the help message.
        if (in_array('help', static::$_argv)) {
            die("usage: php vnserve [bind-address] [bind-port]\n");
        }

        // Check for bad total arguments.
        if (count(static::$_argv) < 3) {
            $listen_addr = static::$_address;
            $listen_port = static::$_port;
        }
        else {
            // Organize input arguments.
            list(, $listen_addr, $listen_port) = static::$_argv;
        }

        echo '[' . date('D M j G:i:s Y') . '] Starting webserver listening on ' . $listen_addr . ':' . $listen_port . PHP_EOL;

        // Start the PHP built-in webserver.
        if (in_array('daemon', static::$_argv)) {

            /**
             * nohup:       Do not terminate this process even when the stty is cut off.
             * > /dev/null: stdout goes to /dev/null (which is a dummy device that does not record any output).
             * 2>&1:        stderr also goes to the stdout (which is already redirected to /dev/null).
             * &:           at the end means to run this command as a background task.
             */
            static::_exec('nohup php -S ' . escapeshellcmd($listen_addr) . ':' . escapeshellcmd($listen_port) . ' > /dev/null 2>&1 & echo $! >> ' . APP_PID_FILE);

            // Check if last process is alive.
            static::_exec_status();
        }
        else {
            // No pre-actions are needs.
            static::_exec('php -S ' . escapeshellcmd($listen_addr) . ':' . escapeshellcmd($listen_port) . '& echo $! >> ' . APP_PID_FILE);
        }

    }

    /**
     * Execute command.
     *
     * @access  private
     * @param   void
     * @return  void
     */
    private static function _exec($command) { 

        exec($command);

        if (!file_exists(APP_PID_FILE)) {
            die('[' . date('D M j G:i:s Y') . "] File " . APP_PID_FILE . " was not found (maybe permissions problem?)\n");
        }

    }

    /**
     * Check process status.
     *
     * @access  private
     * @param   void
     * @return  void
     */
    private static function _exec_status() {

        exec('cat ' . APP_PID_FILE, $buf);

        if (isset($buf[0])) {

            // This sleep is needed to check if process
            // is running.
            sleep(2);

            $pid = (int) $buf[0];
            $buf = [];

            exec("ps ax | grep $pid 2>&1", $buf);

            while (list(, $row) = each($buf)) {
                $row_array = explode(' ', $row);
                $ps_pid = $row_array[1];

                if ($pid == $ps_pid) {
                    echo "- To shutdown the webserver, you must type 'kill -9 " . $pid  . "'\n";
                    return true;
                }
            }
        }

        if (count(static::$_argv) < 3) {
            $listen_addr = static::$_address;
            $listen_port = static::$_port;
        }
        else {
            list(, $listen_addr, $listen_port) = static::$_argv;
        }

        die('[' . date('D M j G:i:s Y') . "] Failed to listen $listen_addr:$listen_port on (Permission denied)\n");

    }

}

$app = new V_Interface($argv);
return $app;
