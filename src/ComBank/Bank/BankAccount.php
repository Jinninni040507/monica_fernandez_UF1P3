<?php namespace ComBank\Bank;

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/27/24
 * Time: 7:25 PM
 */

use ComBank\Exceptions\BankAccountException;
use ComBank\Exceptions\InvalidArgsException;
use ComBank\Exceptions\ZeroAmountException;
use ComBank\OverdraftStrategy\NoOverdraft;
use ComBank\Bank\Contracts\BackAccountInterface;
use ComBank\Exceptions\FailedTransactionException;
use ComBank\Exceptions\InvalidOverdraftFundsException;
use ComBank\OverdraftStrategy\Contracts\OverdraftInterface;
use ComBank\Support\Traits\AmountValidationTrait;
use ComBank\Transactions\Contracts\BankTransactionInterface;


class BankAccount implements BackAccountInterface
{
// AmountValidationTrait
    use AmountValidationTrait;

// Class Attributes
    protected $personHolder;
    protected $balance;
    protected $status;
    protected $overdraft;
    protected $currency;

// Constructor
    public function __construct(float $newBalance = 0.0, $person = null, $currency = null ) {
        $this->personHolder=$person;
        $this->validateAmount($newBalance);
        $this->setBalance($newBalance);
        $this->status = BackAccountInterface::STATUS_OPEN;
        $this->overdraft = new NoOverdraft();
        $this->currency = $currency;
    }


// Methods
    /**
     * Open an account
     */
    public function openAccount(): bool{
        $isOpen = true;
        if ($this->status == BackAccountInterface::STATUS_CLOSED) {
            $isOpen = false;
        }
        return $isOpen;
    }

    /**
     * Reopen an account
     */
    public function reopenAccount(): void{
        if ($this->status == BackAccountInterface::STATUS_CLOSED) {
            $this->setStatus(BackAccountInterface::STATUS_OPEN);
        } else {
            throw new BankAccountException("This account is already open.");
        }
    }

    /**
     * Close an account
     */
    public function closeAccount(): void{
        if ($this->status == BackAccountInterface::STATUS_OPEN) {
            $this->setStatus(BackAccountInterface::STATUS_CLOSED);
        } else {
            throw new BankAccountException("This account is already closed.");
        }
    }

    /**
     * Apply Overdraft
     */
    public function applyOverdraft($OverdraftInterface): void{
        $this->overdraft = $OverdraftInterface;
    }

    /**
     * Make a transaction
     */
    public function transaction(BankTransactionInterface $Transaction):void{
        if ($this->status == BackAccountInterface::STATUS_CLOSED) {
            throw new BankAccountException("Account is closed, transaction cannot be made");
        }
        $Transaction->applyTransaction($this);
    }


// Getters & Setters

    
    // Getters
    /**
     * Get the value of personHolder
     */ 
    public function getPersonHolder()
    {
        return $this->personHolder;
    }

    /**
     * Get the value of balance
     */ 
    public function getBalance(): float
    {
        return $this->balance;
    }

    /**
     * Get the value of overdraft
     */ 
    public function getOverdraft():OverdraftInterface
    {
        return $this->overdraft;
    }

    /**
     * Get the value of currency
     */ 
    public function getCurrency()
    {
        return $this->currency;
    }

    // Setters

    /**
     * Set the value of balance
     *
     * @return  self
     */ 
    public function setBalance($balance):void
    {
        $this->balance = $balance;
    }

    /**
     * Set the value of status
     *
     * @return  self
     */ 
    public function setStatus($status):void
    {
        $this->status = $status;
    }

    /**
     * Set the value of overdraft
     *
     * @return  self
     */ 
    public function setOverdraft(OverdraftInterface $overdraft):void
    {
        $this->overdraft = $overdraft;
    }

    /**
     * Set the value of personHolder
     *
     * @return  self
     */ 
    public function setPersonHolder($personHolder)
    {
        $this->personHolder = $personHolder;

        return $this;
    }

    /**
     * Set the value of currency
     *
     * @return  self
     */ 
    public function setCurrency($currency)
    {
        $this->currency = $currency;

        return $this;
    }
    }
