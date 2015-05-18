<?php

/**
 * VERBENA FRAMEWORK
 * 2015 - MIT LICENSE.
 */

namespace Bootstrap\Components;

use Bootstrap\Environment\Tracer;
use Bootstrap\Environment\Environment;
use Bootstrap\Components\Session;
use Bootstrap\Exceptions\LanguageException;

class Language {

    /**
     * Get the active language.
     *
     * @access  public
     * @param   void
     * @return  mixed
     */
    public static function get_active_language() {

        $_language = Session::get('app_language');

        // Return session language.
        if ($_language !== false) {
            return $_language;
        }
        // Load fallback.
        else {
            $_app_language = Environment::get_env('app.language');

            if (!empty($_app_language)) {
                return $_app_language;
            }
        }

        return false;

    }

    /**
     * Set the environment language.
     *
     * @access  public
     * @param   string
     * @return  bool
     */
    public static function set($_language = 'english') {

        // Get language path.
        $_abs_language = Environment::get_env('paths.language');

        // Clean the language name.
        $_language = basename($_language);

        if (file_exists($_abs_language . $_language)) {
            Session::set('app_language', $_language);
            return true;
        }
        else {
            Tracer::add("[[Language:]] the language [[$_language]] doesn't exists");
            return false;
        }

    }

    /**
     * Get the translated string.
     *
     * @access  public
     * @param   string
     * @param   string
     * @return  mixed
     */
    public static function get($_line = null, $_language_target = null) {

        if (!is_null($_line) and strpos($_line, '.') !== false) {
            // Get the language path and default language app.
            $_abs_language = Environment::get_env('paths.language');
            $_app_language = Environment::get_env('app.language');

            // Get the language file and language line.
            list($_file, $_lang_line) = explode('.', $_line);

            // Get the session language value.
            $_session_lang = Session::get('app_language');

            // Load the language file by target (if specified).
            if (!is_null($_language_target)) {
                $_language_target = basename($_language_target);
                $_language_file = realpath($_abs_language . $_language_target . '/' . $_file . '.php');
            }
            // Check for session language, if doesn't exists
            // load the fallback by environment setting.
            else {
                if ($_session_lang === false) {
                    $_language_file = realpath($_abs_language . $_app_language . '/' . $_file . '.php');
                }
                else {
                    $_language_file = realpath($_abs_language . basename($_session_lang) . '/' . $_file . '.php');
                }
            }

            // Check if language file exists.
            if (file_exists($_language_file)) {
                $_lang_lines = (require $_language_file);

                // If language line found, return it.
                if (isset($_lang_lines[$_lang_line])) {
                    return $_lang_lines[$_lang_line];
                }
                else {
                    Tracer::add('[[Language:]] unable to find language line: [[' . $_lang_line . ']]');
                }
            }
            else {
                Tracer::add('[[Language:]] unable to find language file: [[' . $_language_file . ']]');
            }
        }
        else {
            Tracer::add('[[Language:]] malformed input request for language line '.
                '([[use: Language::("filename.line");]]');
        }

        return false;

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
    
        Tracer::add("[[Language:]] called an undefined method [['$name']]");

        try {
            throw new LanguageException("called an undefined method [['$name']]");
        }
        catch (LanguageException $exception) {
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

        Tracer::add("[[Language:]] called an undefined method [['$name']]");

        try {
            throw new LanguageException("called an undefined method [['$name']]");
        }
        catch (LanguageException $exception) {
            echo $exception->get_formatted_exception();
        }

    
    }

}