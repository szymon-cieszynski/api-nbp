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
        $data = CurrencyModel::getDataFromDatabase();

        View::renderTemplate('Conversion/conversion.html', [
            'data' => $data,
        ]);    
        
    }

    /**
     * Conversion page with converted currencies
     *
     * @return void
     */
    public function conversion()
    {
        $data = CurrencyModel::getDataFromDatabase();
        $fromCurrencyCode = $_POST['from_currency'];
        $toCurrencyCode = $_POST['to_currency'];
        $amount = $_POST['amount'];
        
        $conversionModel = new ConversionModel($amount, $fromCurrencyCode, $toCurrencyCode);

        $conversionData = $conversionModel->convertCurrency();
        if (!empty($conversionData)){
            
            $result = $conversionData['result'];
            $toCurrencyAsk = $conversionData['toCurrencyAsk'];
           
            View::renderTemplate('Conversion/conversion.html', [
                'result' => $result,
                'data' => $data,
                'amount' => $amount,
                'fromCurrency' => $fromCurrencyCode,
                'toCurrency' => $toCurrencyCode,
                'errors' => $conversionModel->errors,
                'rate' => $toCurrencyAsk
            ]);    
        } else {
            View::renderTemplate('Conversion/conversion.html', [
                'data' => $data,
                'errors' => $conversionModel->errors,
            ]); 
        }
        
    }

    /**
     * Show last conversion nad display in table
     *
     * @return void
     */
    public function show()
    {
        $lastConversions = ConversionModel::getLastConversionsFromDatabase();

        View::renderTemplate('Conversion/show.html', [
            'last' => $lastConversions,
        ]);    
        
    }
}
