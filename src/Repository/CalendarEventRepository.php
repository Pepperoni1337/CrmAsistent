<?php

namespace App\Repository;

use App\Entity\Calendar\CalendarEvent;
use DateTime;
use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class CalendarEventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CalendarEvent::class);
    }

    public function findByMonthOffset(DateTimeInterface $date): array
    {
        $qb = $this->createQueryBuilder('ce');

        $qb->andWhere('ce.date LIKE :date')
            ->setParameter('date', $date->format('Y-m-') . '%')
            ->orderBy('ce.date', 'ASC')
            ->addOrderBy('ce.updatedAt', 'DESC');

        return $qb->getQuery()->getResult();
    }
}