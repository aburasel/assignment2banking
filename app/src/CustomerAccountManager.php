<?php

declare(strict_types=1);

namespace App\src;

class CustomerAccountManager  implements CustomerActions
{
    private array $transactions;
    private array $customers;
    private Storage $storage;
    private Customer $customer;

    public function __construct(Storage $storage, Customer $customer)
    {
        $this->storage = $storage;
        $this->customer = $customer;

        $this->transactions = $this->storage->load(Transaction::getModelName());
        $this->customers = $this->storage->load(User::getModelName());
        $amount = 0.0;
        foreach ($this->transactions as $transaction) {
            if ($transaction->getCustomer()->getEmail() == $this->customer->getEmail()) {
                if ($transaction->getTransactionType() == TransactionType::DEPOSIT) {
                    $amount += (float)$transaction->getAmount();
                } else {
                    $amount -= (float)$transaction->getAmount();
                }
            }
        }
        $customer->setCustomerBalance($amount);
    }

    public function transferMoney(string $recepientEmail, float $amount)
    {
        $recepientExist = false;
        $insufficientBalance = false;
        foreach ($this->customers as $cust) {
            if ($cust->getEmail() == $recepientEmail) {
                $recepientExist = true;
                $recepient = $cust;
                if ($this->customer->getCustomerBalance() < $amount) {
                    $insufficientBalance=true;
                }else{
                    $recepient->setCustomerBalance($recepient->getCustomerBalance() + $amount);
                    $this->transactions[] = new Transaction($recepient, TransactionType::DEPOSIT, $amount);
                    $this->saveTransactions();
    
                    $this->customer->setCustomerBalance($this->customer->getCustomerBalance() - $amount);
                    $this->transactions[] = new Transaction($this->customer, TransactionType::WITHDRAW, $amount);
                    $this->saveTransactions();
                }                
                break;
            }
        }
        printf("---------------------------------\n");
        if (!$recepientExist) {
            echo "Recepient email not found\n";
        } else {
            if($insufficientBalance){
                echo "Insufficient Balance\n";
            }else{
                echo "Money Transfered!\n";
            }
        }
        printf("---------------------------------\n");
    }
    public function deposit(float $amount)
    {
        $this->customer->setCustomerBalance($this->customer->getCustomerBalance() + $amount);
        $this->transactions[] = new Transaction($this->customer, TransactionType::DEPOSIT, $amount);
        $this->saveTransactions();
    }

    public function withdraw(float $amount)
    {
        $this->customer->setCustomerBalance($this->customer->getCustomerBalance() - $amount);
        $this->transactions[] = new Transaction($this->customer, TransactionType::WITHDRAW, $amount);
        $this->saveTransactions();
    }

    public function viewTransactions()
    {
        printf("---------------------------------\n");
        foreach ($this->transactions as $transaction) {
            if ($transaction->getCustomer()->getEmail() == $this->customer->getEmail()) {
                printf("Type: %s, Amount: %s\n", $transaction->getTransactionType()->name, $transaction->getAmount());
            }
        }
        printf("---------------------------------\n\n");
    }

    public function accountBalance()
    {
        printf("---------------------------------\n");
        //return $this->customer->getCustomerBalance();
        printf("Your Account Balance: %s\n", $this->customer->getCustomerBalance());
        printf("---------------------------------\n\n");
    }

    public function saveTransactions(): void
    {
        $this->storage->save(Transaction::getModelName(), $this->transactions);
    }
}
