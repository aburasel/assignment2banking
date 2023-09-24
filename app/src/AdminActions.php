<?php
namespace App\src;
interface AdminActions
{
    function viewTransactions(string $email =null);
    function viewCustomers();
}