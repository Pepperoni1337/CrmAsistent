<?php

namespace App\Entity\BankAccount;

enum BankTransactionType: string
{
    case Income = 'income';
    case Expense = 'expense';

    public function isIncome(): bool
    {
        return $this === self::Income;
    }

    public function isExpense(): bool
    {
        return $this === self::Expense;
    }
}
