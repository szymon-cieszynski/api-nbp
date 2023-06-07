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


    public function saveDataToDatabase($data)
    {

        $sql = 'INSERT INTO currency (currency, code, bid, ask)
        VALUES (:currency, :code, :bid, :ask) ON DUPLICATE KEY UPDATE currency = :currency';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        if (isset($data['localCurrency']) && isset($data['localCurrency']['currency']) && isset($data['localCurrency']['code']) && isset($data['localCurrency']['bid']) && isset($data['localCurrency']['ask'])) {
            $localCurrencyData = $data['localCurrency'];
            $stmt->bindValue(':currency', $localCurrencyData['currency'], PDO::PARAM_STR);
            $stmt->bindValue(':code', $localCurrencyData['code'], PDO::PARAM_STR);
            $stmt->bindValue(':bid', $localCurrencyData['bid'], PDO::PARAM_STR);
            $stmt->bindValue(':ask', $localCurrencyData['ask'], PDO::PARAM_STR);
            $stmt->execute();
        }

        foreach ($data['foreignCurrencies'] as $currencyData) {
            if (isset($currencyData['currency']) && isset($currencyData['code']) && isset($currencyData['bid']) && isset($currencyData['ask'])) {
                $stmt->bindValue(':currency', $currencyData['currency'], PDO::PARAM_STR);
                $stmt->bindValue(':code', $currencyData['code'], PDO::PARAM_STR);
                $stmt->bindValue(':bid', $currencyData['bid'], PDO::PARAM_STR);
                $stmt->bindValue(':ask', $currencyData['ask'], PDO::PARAM_STR);
                $stmt->execute();
            }
        }
    
    }

    public function getDataFromDatabase()
    {
        $sql = 'SELECT * FROM currency ORDER BY CASE WHEN code = "PLN" THEN 0 ELSE 1 END, code ASC';
        $db = static::getDB();
        $stmt = $db->query($sql);
        
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $data;
    }
}
