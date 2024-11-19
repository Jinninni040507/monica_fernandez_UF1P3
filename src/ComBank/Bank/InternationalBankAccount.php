<?php namespace ComBank\Bank;

use ComBank\Bank\BankAccount;
use ComBank\Support\Traits\ApiTrait;

class InternationalBankAccount extends BankAccount
{
    use ApiTrait;
    public function getConvertedBalance():float{
        
        $convertedBalance = 0;
        return $convertedBalance;
    }
    public function getConvertedCurrency():string{
        return "hola";
    }
}
    
?>