<?php

namespace App\Entity\BankTransaction;

enum BankTransactionStatus: string
{
    case Paid = 'paid';
    case Pending = 'pending';
}