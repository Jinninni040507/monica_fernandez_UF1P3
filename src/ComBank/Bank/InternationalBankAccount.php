<?php namespace ComBank\Bank;

use ComBank\Bank\BankAccount;
use ComBank\Support\Traits\ApiTrait;

class InternationalBankAccount extends BankAccount
{
    use ApiTrait;

    protected $currency = "$";
    public function getConvertedBalance():float{
        return $this->convertBalance($this->getBalance());
    }
    public function getConvertedCurrency():string{
        return $this->currency;
    }
}
    
?>