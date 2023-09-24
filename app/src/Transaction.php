<?php
namespace App\src;
class Transaction implements Model
{
    protected float $amount;
    protected TransactionType $transactionType;
    protected Customer $customer;
    public function __construct(Customer $customer, TransactionType $type, float $amount) {
        $this->customer = $customer;
        $this->transactionType = $type;
        $this->amount = $amount;
    }
    function getCustomer() : Customer {
        return $this->customer;
    }

    function getAmount() : float {
        return $this->amount;
    }
    function getTransactionType() : TransactionType {
        return $this->transactionType;
    }

    public static function getModelName(): string{
        return 'transactions';
    }
}