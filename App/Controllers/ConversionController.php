<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\CurrencyModel;
use \App\Models\ConversionModel;

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
    public function indexConversion()
    {
        $currencyModel = new CurrencyModel();
        $data = $currencyModel->getDataFromDatabase();


        View::renderTemplate('Conversion/conversion.html', [
            'data' => $data,

        ]);    
        
    }

    public function conversion()
    {
       $data = CurrencyModel::getDataFromDatabase();

       $fromCurrencyData = explode('|', $_POST['from_currency']);
       $fromCurrencyCode = $fromCurrencyData[0];
       $fromCurrencyAsk = $fromCurrencyData[1];
       $amount = $_POST['amount'];
       $toCurrencyData = explode('|', $_POST['to_currency']);
       $toCurrencyCode = $toCurrencyData[0];
       $toCurrencyAsk = $toCurrencyData[1];

        $conversionModel = new ConversionModel();

        $result = $conversionModel->convertCurrency($fromCurrencyAsk, $toCurrencyAsk, $amount);
        

        View::renderTemplate('Conversion/conversion.html', [
            'result' => $result,
            'data' => $data,
            'amount' => $amount,
            'fromCurrency' => $fromCurrencyCode,
            'toCurrency' => $toCurrencyCode,

        ]);    
        
    }
}
