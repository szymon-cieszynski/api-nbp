<?php

namespace App\Models;

use PDO;
use \Core\View;
use \App\Auth;



#[\AllowDynamicProperties]
class ConversionModel extends \Core\Model
{

    public function convertCurrency($fromCurrency, $toCurrency, $amount)
    {
        $result = round($amount * ($fromCurrency / $toCurrency), 2);

        //var_dump($result);die;

        return $result;
    }


    public static function saveConversionToDatabase($amount, $fromCurrencyCode, $toCurrencyCode, $result)
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

    public static function getLastConvertionsFromDatabase()
    {
        $sql = 'SELECT * FROM conversion ORDER BY id DESC LIMIT 10';
        $db = static::getDB();
        $stmt = $db->query($sql);
        
        $lastConvertions = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $lastConvertions;
    }
}
