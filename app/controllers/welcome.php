<?php

/**
 * VERBENA FRAMEWORK
 * 2015 - MIT LICENSE.
 */

namespace App\Controllers;

use Bootstrap\Controllers\Controller;
use Bootstrap\Components\Language;
use Bootstrap\Views\View;

use App\Models\User;

class Welcome extends Controller {

    protected static $view_data;

    /**
     * Constructor.
     *
     * @access  public
     * @param   void
     * @return  void
     */
    public function __construct() {

        // Colors for active language.
        static::$view_data = ['active_english' => '', 'active_italian' => ''];

        if (Language::get_active_language() == 'english') {
            static::$view_data['active_english'] = ' class="active"';
        }
        else {
            static::$view_data['active_italian'] = ' class="active"';
        }

    }

    /**
     * Website switcher.
     *
     * @access  public
     * @param   void
     * @return  void
     */
    public function index() {
        
        View::visualize('welcome', 'layout', static::$view_data);

    }

    /**
     * Display 404 page not found.
     *
     * @access  public
     * @param   void
     * @return  void
     */
    public function notfound() {

        View::visualize('404', 'layout', static::$view_data);

    }

}