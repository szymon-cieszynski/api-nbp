<?php

namespace App\Controllers;

use \Core\View;

/**
 * Home controller
 *
 */
class HomeController extends \Core\Controller
{

    /**
     * Show the index page
     *
     * @return void
     */
    public function indexAction()
    {
        View::renderTemplate('Home/index.html');
        
    }
}
