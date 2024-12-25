<?php

namespace App\Controller\BankAccount;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TransactionsAction extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    #[Route('/bank-account-transactions', name: 'bank_account_transactions', methods: ['GET'])]
    public function __invoke(): Response
    {
        return $this->render('bank_account/transactions.html.twig');
    }
}