<?php

/**
 * VERBENA FRAMEWORK
 * 2015 - MIT LICENSE.
 */

namespace Bootstrap\Exceptions;

use Exception;

class CacheException extends Exception {

    /**
     * Exception variables.
     * @var string|integer
     */
    protected $message = 'Unknown exception';   // Exception message
    private   $string;                          // Unknown
    protected $code = 0;                        // User-defined exception code
    protected $file;                            // Source filename of exception
    protected $line;                            // Source line of exception
    private   $trace;                           // Unknown


    /**
     * Override the constructor.
     *
     * @access  public
     * @param   string
     * @param   int
     * @param   exception
     * @return  void
     */
    public function __construct($message, $code = 0, Exception $previous = null) {

        parent::__construct($message, $code, $previous);
    
    }

    /**
     * Override the __toString() method.
     *
     * @access  public
     * @param   void
     * @return  void
     */
    public function __toString() {

        return __CLASS__ . ": [{$this->line}]: {$this->message} - \n";

    }

    /**
     * Override the __toString() method.
     *
     * @access  public
     * @param   void
     * @return  void
     */
    public function get_formatted_exception() {

        $this->message = str_replace('[[', '<strong>', $this->message);
        $this->message = str_replace(']]', '</strong>', $this->message);

        return '<style>
        .debug-line {
            padding:8px;
            color:#c7254e;
            background-color:#f9f2f4;
            border-radius:4px;
            float: left;
            font-size:12px!important;
            clear: both;
            padding: 10px 20px 10px 20px;
            z-index: 9999999;
            border-radius: 0;
        }
        strong { color: #111 }
        </style>
        <code class="debug-line"><strong>Fatal error:</strong> ' . __CLASS__ . ": [{$this->line}]: {$this->message} - thrown in <strong>{$this->file}</strong> on line <strong>{$this->line}</strong></code>\n";

    }
    
}