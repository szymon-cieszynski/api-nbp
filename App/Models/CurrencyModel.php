<?php

namespace App\Models;

use PDO;
use \Core\View;
use \App\Auth;



#[\AllowDynamicProperties]
class CurrencyModel extends \Core\Model
{

    public function fetchDataFromNBP()
    {
        $apiUrl = 'https://api.nbp.pl/api/exchangerates/tables/c/';
            
        // Fetching data from API
        $jsonResponse = file_get_contents($apiUrl);

        $data = json_decode($jsonResponse, true);

        $localCurrency = array(
            'currency' => 'Polski złoty',
            'code' => 'PLN',
            'bid' => '1.00',
            'ask' => '1.00'
        );

        $foreignCurrencies = array_map(function($rate) {
            return array(
                'currency' => $rate['currency'],
                'code' => $rate['code'],
                'bid' => $rate['bid'],
                'ask' => $rate['ask'],
            );
        }, $data[0]['rates']);
    
    
        $combinedData = array(
            'localCurrency' => $localCurrency,
            'foreignCurrencies' => $foreignCurrencies
            
        );
        
        return $combinedData;
    }

    public function saveDataToDatabase($data)
    {
        $sql = 'INSERT INTO currency (currency, code, bid, ask) VALUES (:currency, :code, :bid, :ask)';

        $db = static::getDB();
        $db->exec('TRUNCATE TABLE currency');
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

    public static function getDataFromDatabase()
    {
        $sql = 'SELECT * FROM currency ORDER BY CASE WHEN code = "PLN" THEN 0 ELSE 1 END, code ASC';
        $db = static::getDB();
        $stmt = $db->query($sql);
        
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $data;
    }
}
