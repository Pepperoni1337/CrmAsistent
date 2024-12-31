<?php

namespace App\Controller\BankAccount;

use App\Entity\BankAccount\BankTransaction;
use App\Repository\BankTransactionRepository;
use App\Utils\DateTimeUtils;
use DateTimeImmutable;
use DateTimeInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TransactionsAction extends AbstractController
{
    public function __construct(
        private readonly BankTransactionRepository $repository,
    ) {
    }

    #[Route('/bank-account-transactions', name: 'bank_account_transactions', methods: ['GET'])]
    public function __invoke(Request $request): Response
    {
        $offset = $request->query->getInt('offset', 0);

        $date = new DateTimeImmutable('first day of this month');

        $date1 = DateTimeUtils::offsetMonth($date, $offset - 1);
        $date2 = DateTimeUtils::offsetMonth($date, $offset);
        $date3 = DateTimeUtils::offsetMonth($date, $offset + 1);


        return $this->render(
            'bank_account/transactions.html.twig',
            [
                'offset' => $offset,
                'transactions' => [
                    $this->getMonthData($date1),
                    $this->getMonthData($date2),
                    $this->getMonthData($date3),
                ],
            ],
        );
    }

    /**
     * @return array<string, string|float|BankTransaction[]>
     */
    private function getMonthData(DateTimeInterface $date): array
    {
        $transactions = $this->repository->findByMonth($date);

        return [
            'month' => $date->format('m'),
            'year' => $date->format('Y'),
            'transactions' => $transactions,
            'incomeSum' => $this->getIncomeSum($transactions),
            'expenseSum' => $this->getExpenseSum($transactions),
            'totalSum' => $this->getTotalSum($transactions),
        ];
    }

    /**
     * @param BankTransaction[] $transactions
     */
    private function getIncomeSum(array $transactions): float
    {
        $sum = 0.0;
        foreach ($transactions as $transaction) {
            if ($transaction->getType()->isIncome()) {
                $sum += $transaction->getAmount();
            }
        }

        return $sum;
    }

    /**
     * @param BankTransaction[] $transactions
     */
    private function getExpenseSum(array $transactions): float
    {
        $sum = 0.0;
        foreach ($transactions as $transaction) {
            if ($transaction->getType()->isExpense()) {
                $sum += $transaction->getAmount();
            }
        }

        return $sum;
    }

    /**
     * @param BankTransaction[] $transactions
     */
    private function getTotalSum(array $transactions): float
    {
        $sum = 0.0;
        foreach ($transactions as $transaction) {
            if ($transaction->getType()->isIncome()) {
                $sum += $transaction->getAmount();
            } else {
                $sum -= $transaction->getAmount();
            }
        }

        return $sum;
    }
}