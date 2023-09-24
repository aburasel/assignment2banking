<?php

declare(strict_types=1);

namespace App\src;

class AdminAccountManager  implements AdminActions
{
    private array $transactions;
    private array $customers;
    private Storage $storage;
    private Admin $admin;

    public function __construct(Storage $storage, Admin $admin)
    {
        $this->storage = $storage;
        $this->admin = $admin;

        $this->transactions = $this->storage->load(Transaction::getModelName());
        $this->customers = $this->storage->load(User::getModelName());
    }

    public function viewCustomers()
    {
        printf("---------------------------------\n");
        foreach ($this->customers as $customer) {
            if ($customer->getAccessLevel() == AccessLevel::CUSTOMER) {
                printf("Name: %s, Email: %s\n", $customer->getName(), $customer->getEmail());
            }
        }
        printf("---------------------------------\n\n");
    }

    public function viewTransactions(string $email = null)
    {
        printf("---------------------------------\n");
        if ($email == null) {
            foreach ($this->transactions as $transaction) {
                printf("Customer: %s, Type: %s, Amount: %s\n", $transaction->getCustomer()->getEmail(), $transaction->getTransactionType()->name, $transaction->getAmount());
            }
        } else {
            foreach ($this->transactions as $transaction) {
                if ($transaction->getCustomer()->getEmail() == $email) {
                    printf("Type: %s, Amount: %s\n", $transaction->getTransactionType()->name, $transaction->getAmount());
                }
            }
        }

        printf("---------------------------------\n\n");
    }
}
