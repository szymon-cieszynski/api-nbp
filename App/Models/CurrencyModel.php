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
        $apiUrl = 'http://api.nbp.pl/api/exchangerates/tables/A/';
            
        // Fetching data from API
        $jsonResponse = file_get_contents($apiUrl);
        //var_dump($jsonResponse);
        $data = json_decode($jsonResponse, true);

        $filteredData = array_map(function($rate) {
            return array(
                'currency' => $rate['currency'],
                'code' => $rate['code'],
                'mid' => $rate['mid']
            );
        }, $data[0]['rates']);
    
        return $filteredData;
    }

    public function saveDataToDatabase($data)
    {

        $sql = 'INSERT INTO currency (currency, code, mid)
        VALUES (:currency, :code, :mid) ON DUPLICATE KEY UPDATE mid = :mid';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        foreach ($data as $currencyData) {
            if (isset($currencyData['currency']) && isset($currencyData['code']) && isset($currencyData['mid'])) {
                $stmt->bindValue(':currency', $currencyData['currency'], PDO::PARAM_STR);
                $stmt->bindValue(':code', $currencyData['code'], PDO::PARAM_STR);
                $stmt->bindValue(':mid', $currencyData['mid'], PDO::PARAM_STR);
                $stmt->execute();
            }
        }
    }

    public function getDataFromDatabase()
    {
        $sql = 'SELECT currency, code, mid FROM currency';
        $db = static::getDB();
        $stmt = $db->query($sql);
        
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $data;
    }
}
