<?php

namespace App\Entity\BankTransaction;

enum BankTransactionType: string
{
    case Income = 'income';
    case Expense = 'expense';
}
