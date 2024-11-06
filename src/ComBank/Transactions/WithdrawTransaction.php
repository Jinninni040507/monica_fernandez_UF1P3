<?php namespace ComBank\Transactions;

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/28/24
 * Time: 1:22 PM
 */

use ComBank\Bank\Contracts\BackAccountInterface;
use ComBank\Exceptions\InvalidOverdraftFundsException;
use ComBank\Transactions\Contracts\BankTransactionInterface;
use ComBank\Exceptions\FailedTransactionException;

class WithdrawTransaction 
extends BaseTransaction
implements BankTransactionInterface
{
    function applyTransaction(BackAccountInterface $bankAccount):float
    {
        $newBalance = $bankAccount->getBalance() - $this->amount;

        
        if (!$bankAccount->getOverdraft()->isGrantOverdraftFunds($newBalance)) {
            if ($bankAccount->getOverdraft()->getOverdraftFundsAmount()==0) {
                throw new InvalidOverdraftFundsException('Insufficient balance to complete the withdrawal');
            }
            throw new FailedTransactionException('Withdrawal exdeeds overdraft limit.');
            
        } else {
            $bankAccount->setBalance($newBalance);
            
            return $newBalance;
        }

    }
    function getTRansactionInfo():string{
        return 'WITHDRAW_TRANSACTION';
    }
    function getAmount():float{
        return $this->amount;
    }
}
