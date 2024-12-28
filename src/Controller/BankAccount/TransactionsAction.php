<?php

namespace App\Controller\BankAccount;

use App\Repository\BankTransactionRepository;
use App\Utils\DateTimeUtils;
use DateTimeImmutable;
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
                    [
                        'month' => $date1->format('m'),
                        'year' => $date1->format('Y'),
                        'transactions' => $this->repository->findByMonthOffset($date1),
                    ],
                    [
                        'month' => $date2->format('m'),
                        'year' => $date2->format('Y'),
                        'transactions' => $this->repository->findByMonthOffset($date2),
                    ],
                    [
                        'month' => $date3->format('m'),
                        'year' => $date3->format('Y'),
                        'transactions' => $this->repository->findByMonthOffset($date3),
                    ],
                ],
            ],
        );
    }
}