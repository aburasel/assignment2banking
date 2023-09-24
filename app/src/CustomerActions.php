<?php
namespace App\src;
interface CustomerActions
{
    function transferMoney(String $recepientEmail, float $amount);
    function deposit(float $amount);
    function withdraw(float $amount);
    function viewTransactions();
    function accountBalance();
}