<?php

namespace App\Models;

use PDO;
use \Core\View;
use \App\Auth;



#[\AllowDynamicProperties]
class ConversionModel extends \Core\Model
{

    public $errors = [];

    /**
     * Class constructor
     * @param array $data  Initial property values
     */
    public function __construct($amount, $fromCurrencyCode, $toCurrencyCode) //during creating object, assign values from form
    {
        $this->amount = $amount;
        $this->toCurrencyCode = $toCurrencyCode;
        $this->fromCurrencyCode = $fromCurrencyCode;
    }

    public function convertCurrency()
    {
        $this->dataValidation();

        if(!$this->errors)
        {
            $fromCurrencyAsk = ConversionModel::askRateForCurrency($this->fromCurrencyCode)['ask'];
            $toCurrencyAsk = ConversionModel::askRateForCurrency($this->toCurrencyCode)['ask'];

            $result = round($this->amount * ($fromCurrencyAsk / $toCurrencyAsk), 2);
            $this->saveConversionToDatabase($this->amount, $this->fromCurrencyCode, $this->toCurrencyCode, $result);
            
            return [
                'result' => $result,
                'toCurrencyAsk' => $toCurrencyAsk,
            ];

        } else {
            return false;
        }
        
    }

    private static function askRateForCurrency($currencyCode)
    {
        $sql = 'SELECT ask FROM currency WHERE code = :code';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':code', $currencyCode, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    private function dataValidation()
    {
        //amount validation
        if (!isset($this->amount) || $this->amount <= 0) {
            $this->errors[] = 'Amount should be a positive number';
        } else if((int)($this->amount >= 10000000))
            $this->errors[] = 'Amount sohuld be less than 10.000.000PLN';
        
        
        if (empty($this->fromCurrencyCode)) {
            $this->errors[] = 'Choose source currency!';
        }

        if (empty($this->toCurrencyCode)) {
            $this->errors[] = 'Choose target currency!';
        }
    }


    private function saveConversionToDatabase($amount, $fromCurrencyCode, $toCurrencyCode, $result)
    {

        $sql = 'INSERT INTO conversion (amount, from_currency, to_currency, result)
        VALUES (:amount, :from_currency, :to_currency, :result)';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':amount', $amount, PDO::PARAM_STR);
        $stmt->bindValue(':from_currency', $fromCurrencyCode, PDO::PARAM_STR);
        $stmt->bindValue(':to_currency', $toCurrencyCode, PDO::PARAM_STR);
        $stmt->bindValue(':result', $result, PDO::PARAM_STR);

        return $stmt->execute();        
    
    }

    public static function getLastConversionsFromDatabase()
    {
        $sql = 'SELECT * FROM conversion ORDER BY id DESC LIMIT 10';
        $db = static::getDB();
        $stmt = $db->query($sql);
        
        $lastConversions = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $lastConversions;
    }
}
