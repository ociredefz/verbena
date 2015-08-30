<?php

/**
 * VERBENA FRAMEWORK
 * 2015 - MIT LICENSE.
 */

namespace Bootstrap\Controllers;

use Bootstrap\Environment\Tracer;
use Bootstrap\Components\Language;
use Bootstrap\Components\Security;
use Bootstrap\Views\View;
use Bootstrap\Exceptions\ControllerException;

use App\Models\Followers;

class Controller {

    /**
     * Instances container.
     * @var object
     */
    protected static $_instances = [];

    /**
     * View variables.
     * @var array
     */
    protected static $view = [];


    /**
     * Create the controller instance.
     *
     * @access  public
     * @param   void
     * @return  object
     */
    public static function instance_class() {
        
        $_class = get_called_class();

        if (!isset(static::$_instances[$_class])) {
            static::$_instances[$_class] = new $_class();
        }

        return static::$_instances[$_class];

    }

    /**
     * Get the name of the current called method.
     *
     * @access  public
     * @param   void
     * @return  string
     */
    public static function get_current_called_method() {

        $_traces = debug_backtrace();

        if (isset($_traces[3]['args'][0]['method'])) {
            return $_traces[3]['args'][0]['method']; 
        } 

        return null; 

    }

    /**
     * Set session language and redirect 
     * to the webroot (if not null).
     *
     * @access  public
     * @param   string
     * @param   bool
     * @return  void
     */
    public static function language($_language = null, $_redirect = null) {

        if (!is_null($_language)) {
            Language::set($_language);
        }

        if (is_null($_redirect)) {
            View::redirect();
        }

    }

    /**
     * Initialize conversation.
     *
     * @access  public
     * @param   integer
     * @return  void
     */
    public static function conversation($user_id) {

        // Check valid user id before initialize
        // the conversation system.
        if (empty($user_id)) {
            return false; 
        }

        // Retrieve user followers.
        $followers = Followers::get_followers($user_id);
        $users_list = '';

        if (!empty($followers)) {
            foreach ($followers as $key => $value) {
                $encoded_id = md5(Security::generate_secure_token());
                $name = substr(Security::filter_xss(ucfirst($value->fullname), true), 0, 256);
                
                // Set fallback for invalid/empty name.
                if (empty($name) or strlen($name) <= 2) {
                    $name = '(Invalid name)';
                }

                $users_list .= '<div id="conv-user-' . $encoded_id . '" class="conv-user conv-user-' . $encoded_id . '">
                    <span class="pull-right status">Online</span>
                    <img class="avatar" src="http://detekt.mooo.com/assets/images/avatars/avatar.png" alt="">
                    <div class="name">
                        <a id="conv-name-' . $encoded_id . '" href="javascript:void(0)">' . $name . '</a>
                    </div>
                </div>';
            }
        }

        // Generate conversation code.
        static::$view['conversation'] = 
        '<!-- Conversation side. -->
        <div id="conv-side-box">
            <!-- Conversation chat bar. -->
            <a id="conv-side-chat-bar" href="#">
                <div>
                    <em class="on"></em>
                    <span class="user-list-text">Chat</span>
                </div>
                <span class="user-counter-text">(2)</span>
            </a>
            <!-- Conversation users list. -->
            <div id="conv-side-ulist-box" class="hidden">
                <div id="conv-side-ulist-header">
                    <span>Start conversation</span>
                    <span><i class="fa fa-cog"></i></span>
                </div>
                <div id="conv-side-ulist-body">
                    ' . $users_list . '
                </div>
            </div>
        </div>
        <!-- Conversation windows container. -->
        <div id="conv-windows"></div>';
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

        Tracer::add("[[Controller:]] called an undefined method [['$name']]");

        try {
            throw new ControllerException("called an undefined method [['$name']]");
        }
        catch (ControllerException $exception) {
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

        Tracer::add("[[Controller:]] called an undefined method [['$name']]");

        try {
            throw new ControllerException("called an undefined method [['$name']]");
        }
        catch (ControllerException $exception) {
            echo $exception->get_formatted_exception();
        }
    
    }

}