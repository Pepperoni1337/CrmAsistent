<?php

namespace App\Entity\BankAccount;

enum BankTransactionStatus: string
{
    case Paid = 'paid';
    case Pending = 'pending';
}