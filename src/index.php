<?php

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/27/24
 * Time: 7:24 PM
 */

use ComBank\Bank\BankAccount;
use ComBank\Bank\InternationalBankAccount;
use ComBank\Bank\NationalBankAccount;
use ComBank\person\Person;
use ComBank\OverdraftStrategy\SilverOverdraft;
use ComBank\Transactions\DepositTransaction;
use ComBank\Transactions\WithdrawTransaction;
use ComBank\Exceptions\BankAccountException;
use ComBank\Exceptions\FailedTransactionException;
use ComBank\Exceptions\ZeroAmountException;
use ComBank\Exceptions\InvalidOverdraftFundsException;
use ComBank\Exceptions\InvalidEmailException;
use ComBank\Exceptions\FraudTransactionException;

require_once 'bootstrap.php';


//---[Bank account 1]---/
// create a new account1 with balance 400
pl('--------- [Start testing bank account #1, No overdraft] --------');
try {

    $bankAccount1 = new BankAccount(400.0);
    // show balance account
    pl("My balance : ". $bankAccount1->getBalance());
    // close account
    $bankAccount1->closeAccount();
    pl('My account is now closed.');
    // reopen account
    $bankAccount1->reopenAccount();
    pl('My account is now reopened.');

    // deposit +150 
    
    pl('Doing transaction deposit (+150) with current balance ' . $bankAccount1->getBalance());
    $bankAccount1->transaction(new DepositTransaction(150.0));

    pl('My new balance after deposit (+150) : ' . $bankAccount1->getBalance());

    // withdrawal -25
    pl('Doing transaction withdrawal (-25) with current balance ' . $bankAccount1->getBalance());

    $bankAccount1->transaction(new WithdrawTransaction(25));
    pl('My new balance after withdrawal (-25) : ' . $bankAccount1->getBalance());

    // withdrawal -600
    pl('Doing transaction withdrawal (-600) with current balance ' . $bankAccount1->getBalance());
    $bankAccount1->transaction(new WithdrawTransaction(600));


} catch (ZeroAmountException $e) {
    pl($e->getMessage());
} catch (BankAccountException $e) {
    pl($e->getMessage());
} catch (FailedTransactionException $e) {
    pl('Error transaction: ' . $e->getMessage());
} catch (InvalidOverdraftFundsException $e) {
    pl('Error transaction: ' . $e->getMessage());
}
pl('My balance after failed last transaction : ' . $bankAccount1->getBalance());

$bankAccount1->closeAccount();
pl('My account is now closed.');


//---[Bank account 2]---/
pl('--------- [Start testing bank account #2, Silver overdraft (100.0 funds)] --------');
try {
    
    // Creating & setting bankAccount2

    $bankAccount2 = new BankAccount(200);
    $bankAccount2->setOverdraft(new SilverOverdraft());


    // show balance account
    pl('My balance '. $bankAccount2->getBalance());
    // deposit +100
    pl('Doing transaction deposit (+100) with current balance ' . $bankAccount2->getBalance());
    $bankAccount2->transaction(new DepositTransaction(100));
    
    pl('My new balance after deposit (+100) : ' . $bankAccount2->getBalance());

    // withdrawal -300
    pl('Doing transaction withdrawal (-300) with current balance ' . $bankAccount2->getBalance());

    $bankAccount2->transaction(new WithdrawTransaction(300));
   
    pl('My new balance after withdrawal (-300) : ' . $bankAccount2->getBalance());

    // withdrawal -50
    pl('Doing transaction withdrawal (-50) with current balance ' . $bankAccount2->getBalance());

    $bankAccount2->transaction(new WithdrawTransaction(50));
    
    pl('My new balance after withdrawal (-50) with funds : ' . $bankAccount2->getBalance());

    // withdrawal -120
    pl('Doing transaction withdrawal (-120) with current balance ' . $bankAccount2->getBalance());

    $bankAccount2->transaction(new WithdrawTransaction(120));

    
} catch (FailedTransactionException $e) {
    pl('Error transaction: ' . $e->getMessage());
}
pl('My balance after failed last transaction : ' . $bankAccount2->getBalance());

try {
    pl('Doing transaction withdrawal (-20) with current balance : ' . $bankAccount2->getBalance());

    $bankAccount2->transaction(new WithdrawTransaction(20));
    
} catch (FailedTransactionException $e) {
    pl('Error transaction: ' . $e->getMessage());
}
pl('My new balance after withdrawal (-20) with funds : ' . $bankAccount2->getBalance());

$bankAccount2->closeAccount();
pl('My account is now closed.');

try {
    $bankAccount2->closeAccount();

} catch (BankAccountException $e) {
    pl($e->getMessage());
}


//---INTERNATIONAL || NATIONAL ACCOUNTS---/
pl('--------- [Start testing INTERNATIONAL bank account #3] --------');


// 1. Verify national bank account returns currency in €
$persona1 = new Person("Miquel", 1, "mafcorrales1970@gmail.com");
$banckAccount3 = new NationalBankAccount(1000, 0,$persona1);
pl("Creating National Bank Account");
pl("Currency: ".$banckAccount3->getCurrency());
pl("Balance: ".$banckAccount3->getBalance().$banckAccount3->getCurrency());


// 2. Verify international bank account returns currency in € with no converted balance
$banckAccount4 = new InternationalBankAccount(1000, 0,$persona1);
pl("Creating National Bank Account");
pl("Currency: ".$banckAccount4->getCurrency());
pl("Balance: ".$banckAccount4->getBalance().$banckAccount3->getCurrency());

// 3. Verify international bank account returns currency in $ with balance converted
pl("Converted Balance: ".$banckAccount4->getConvertedBalance().$banckAccount4->getConvertedCurrency());

// 4. Verify a valid email for an account holder
pl("Creating person with email mafcorrales1970@gmail.com");
$persona2 = new Person("Miquel", 1, "mafcorrales1970@gmail.com");
pl($persona2->getEmail());

// 5. Verify an invalid email for an account holder
try {
    pl("Creating person with email mafcorrales1970@gmail.com");
    $persona2 = new Person("Miquel", 1, "mafcorrales1970@@gmail.com");
} catch (InvalidEmailException $th) {
    pl("Invalid Email");
}

// 6. Verify deposit allowed by fraud functionality
pl('Doing transaction deposit (+150) with current balance ' . $bankAccount3->getBalance());
$bankAccount3->transaction(new DepositTransaction(150.0));
    
// 7. Verify deposit blocked by fraud functionality

try {
    pl('Doing transaction deposit (+50000) with current balance ' . $bankAccount3->getBalance());
    $bankAccount3->transaction(new DepositTransaction(50000));
} catch (FraudTransactionException $th) {
    pl("Fraud detected");
}

// 8. Verify withdraw allowed by fraud functionality
pl('Doing transaction deposit (+150) with current balance ' . $bankAccount3->getBalance());
$bankAccount3->transaction(new WithdrawTransaction(50));
// 9. Verify withdraw blocked by fraud functionality
try {
    pl('Doing transaction deposit (+50000) with current balance ' . $bankAccount3->getBalance());
    $bankAccount3->transaction(new WithdrawTransaction(50000));
} catch (FraudTransactionException $th) {
    pl("Fraud detected");
}

// 10. Verify new free functionality



