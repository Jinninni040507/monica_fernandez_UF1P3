<?php namespace ComBank\Bank;

use ComBank\Bank\BankAccount;

class NationalBankAccount extends BankAccount
{
    protected $currency = "€";
    public function getConvertedCurrency():string{
        return $this->currency;
    }
}
    
?>