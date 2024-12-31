<?php

namespace App\Controller\BankAccount;

use App\Entity\BankAccount\BankTransaction;
use App\Entity\BankAccount\BankTransactionStatus;
use App\Entity\BankAccount\BankTransactionType;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CreateTransactionAction extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em
    )
    {
    }

    #[Route('/bank-account-transactions/create_transaction', name: 'bank_account_transactions_create', methods: ['GET', 'POST'])]
    public function __invoke(Request $request): Response
    {
        if ($request->getMethod() === 'POST') {

            $date = new DateTimeImmutable($request->get('date'));

            $type = $request->get('type') === 'income' ? BankTransactionType::Income : BankTransactionType::Expense;

            $entity = new BankTransaction(
                name: $request->request->get('name'),
                date: $date,
                type:$type,
                amount: (float)$request->request->get('amount'),
                status: BankTransactionStatus::Pending,
            );

            $this->em->persist($entity);
            $this->em->flush();

            return $this->redirectToRoute('bank_account_transactions');
        }

        return $this->render(
            'bank_account/create_transaction.html.twig',
            [],
        );
    }
}