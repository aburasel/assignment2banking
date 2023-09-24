<?php
namespace App\src;
enum TransactionType : string
{
    case DEPOSIT ="DEPOSIT";
    case WITHDRAW="WITHDRAW";
    case TRANSFER="TRANSFER";
}
