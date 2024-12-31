<?php

namespace App\Repository;

use App\Entity\BankAccount\BankTransaction;
use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BankTransaction>
 */
final class BankTransactionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BankTransaction::class);
    }

    /**
     * @return BankTransaction[]
     */
    public function findByMonth(DateTimeInterface $date): array
    {
        $qb = $this->createQueryBuilder('bt');

        $qb->andWhere('bt.date LIKE :date')
            ->setParameter('date', $date->format('Y-m-') . '%')
            ->orderBy('bt.date', 'ASC')
            ->addOrderBy('bt.updatedAt', 'DESC');

        return $qb->getQuery()->getResult();
    }
}