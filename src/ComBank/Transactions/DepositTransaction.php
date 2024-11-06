<?php namespace ComBank\Transactions;

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/28/24
 * Time: 11:30 AM
 */

use ComBank\Bank\Contracts\BackAccountInterface;
use ComBank\Transactions\Contracts\BankTransactionInterface;

class DepositTransaction 
extends BaseTransaction
implements BankTransactionInterface
{
   
    function applyTransaction(BackAccountInterface $bankAccount):float{
        $newBalance = $bankAccount->getBalance() + $this->amount; 
        $bankAccount->setBalance($newBalance); 

        return $bankAccount->getBalance();
    }
    function getTRansactionInfo():string{
        return 'DEPOSIT_TRANSACTION';
    }
    function getAmount():float{
        return $this->amount;
    }

   
}
