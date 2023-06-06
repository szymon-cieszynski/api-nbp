<?php

namespace App\Controllers;

use \Core\View;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Home extends \Core\Controller
{

    /**
     * Show the index page
     *
     * @return void
     */
    public function indexAction()
    {
        // $conn = static::getDB();
        // if ($conn->connect_error) {
        //     die("Błąd połączenia z bazą danych: " . $conn->connect_error);
        // }

        View::renderTemplate('Home/index.html');
   

        
        
    }
}
