<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\CurrencyModel;

/**
 * Conversion controller
 *
 */
class ConversionController extends \Core\Controller
{

    /**
     * Show the current data page
     *
     * @return void
     */
    public function conversion()
    {

        View::renderTemplate('Conversion/conversion.html');    
        
    }
}
