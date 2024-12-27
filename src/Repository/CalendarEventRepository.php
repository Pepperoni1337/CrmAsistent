<?php

namespace App\Repository;

use App\Entity\Calendar\CalendarEvent;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CalendarEventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CalendarEvent::class);
    }

    public function findThisMonthEvents(): array
    {
        $qb = $this->createQueryBuilder('ce');

        $qb
            ->andWhere('ce.month = :month')
            ->andWhere('ce.year = :year')
            ->setParameter('month', date('m'))
            ->setParameter('year', date('Y'))
            ->orderBy('ce.year', 'ASC')
            ->addOrderBy('ce.month', 'ASC')
            ->addOrderBy('ce.day', 'ASC')
        ;

        return $qb->getQuery()->getResult();
    }

    public function findNextMonthEvents(): array
    {
        $qb = $this->createQueryBuilder('ce');

        $nextMonth = (new DateTime('first day of next month'));

        $qb
            ->where('ce.month = :nextMonth')
            ->andWhere('ce.year = :year')
            ->setParameter('nextMonth', $nextMonth->format('m'))
            ->setParameter('year', $nextMonth->format('Y'))
            ->orderBy('ce.updatedAt', 'DESC');

        return $qb->getQuery()->getResult();
    }
}