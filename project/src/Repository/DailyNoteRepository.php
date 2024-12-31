<?php

namespace App\Repository;

use App\Entity\DailyNote\DailyNote;
use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class DailyNoteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DailyNote::class);
    }

    public function findByDay(DateTimeInterface $date): array {
        $qb = $this->createQueryBuilder('dn');

        $qb->where(
            $qb->expr()->eq('date(dn.createdAt)', ':date')
        )
        ->setParameter('date', $date->format('Y-m-d'));

        return $qb->getQuery()->getResult();
    }

}