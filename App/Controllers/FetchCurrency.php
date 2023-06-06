<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\CurrencyModel;

/**
 * Fetch Currency controller
 *
 */
class FetchCurrency extends \Core\Controller
{

    /**
     * Show the current data page
     *
     * @return void
     */
    public function fetch()
    {
        $currencyModel = new CurrencyModel();
        $data = $currencyModel->fetchDataFromNBP();
        //var_dump($data);

        $currencyModel->saveDataToDatabase($data);

        $data = $currencyModel->getDataFromDatabase();
       // var_dump($data);

        View::renderTemplate('FetchCurrency/fetch.html', [
            'data' => $data
        ]);    
        
    }
}
