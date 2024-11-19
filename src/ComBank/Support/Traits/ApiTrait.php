<?php namespace ComBank\Support\Traits;

use ComBank\Transactions\Contracts\BankTransactionInterface;

trait ApiTrait {
    function validateEmail(string $email):bool{

        $ch = curl_init("https://emailvalidation.abstractapi.com/v1/?api_key=2f9fe36abae640af8f3e584d65fdff39&email=$email");
        curl_setopt_array($ch,[
            CURLOPT_RETURNTRANSFER => true
        ]);
        $resultJson = json_decode(curl_exec($ch));

        $isValidFormat = $resultJson["is_valid_format"]["value"];
        $isDisposableEmail = $resultJson["is_disposable_email"]["value"];
        $isSMTPValid = $resultJson["is_smtp_valid"]["value"];

        return $isValidFormat && !$isDisposableEmail && $isSMTPValid;
    }
    function convertBalance(float $originalBalance):float{
        $ch = curl_init("https://api.freecurrencyapi.com/v1/latest?apikey=fca_live_hX54YPYC6N6yb56nB5vkdUezKOkAFVWT6cfRoslL&currencies=USD&base_currency=EUR");
        curl_setopt_array($ch,[
            CURLOPT_RETURNTRANSFER => true
        ]);
        $resultJson = curl_exec($ch);
        curl_close($ch);

        $conversionRate = json_decode($resultJson,true)["data"]["USD"];

        return $originalBalance*$conversionRate;
    }
    function detectFraud(BankTransactionInterface $transaction):bool{
        $ch = curl_init("https://673c935696b8dcd5f3fa9e88.mockapi.io/api/bank/fraud");
        curl_setopt_array($ch,[
            CURLOPT_RETURNTRANSFER => true
        ]);
        $resultJson = curl_exec($ch);
        curl_close($ch);
        $frauds = json_decode($resultJson,true);

        

        return true;
    }
}
    
?>