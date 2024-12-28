<?php

namespace App\Repository;

use App\Entity\Calendar\CalendarEvent;
use App\Entity\Calendar\CalendarEventType;
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
        $date2 = new DateTime($date->format('Y-m-d'));
        $date2->modify('+3 month');

        $date3 = new DateTime($date->format('Y-m-d'));
        $date3->modify('+6 month');

        $date4 = new DateTime($date->format('Y-m-d'));
        $date4->modify('+9 month');

        $qb = $this->createQueryBuilder('ce');

        $qb->where(
                $qb->expr()->andX(
                    'ce.type = :onetime',
                    'ce.date LIKE :onetimeDate'
                )
            )
            ->orWhere('ce.type = :monthly',)
            ->orWhere(
                $qb->expr()->andX(
                    'ce.type = :quarterly',
                    'ce.date LIKE :quarterlyDate1'
                )
            )
            ->orWhere(
                $qb->expr()->andX(
                    'ce.type = :quarterly',
                    'ce.date LIKE :quarterlyDate2'
                )
            )
            ->orWhere(
                $qb->expr()->andX(
                    'ce.type = :quarterly',
                    'ce.date LIKE :quarterlyDate3'
                )
            )
            ->orWhere(
                $qb->expr()->andX(
                    'ce.type = :quarterly',
                    'ce.date LIKE :quarterlyDate4'
                )
            )
            ->orWhere(
                $qb->expr()->andX(
                    'ce.type = :yearly',
                    'ce.date LIKE :yearlyDate'
                )
            )
            ->setParameter('onetime', CalendarEventType::OneTime)
            ->setParameter('monthly', CalendarEventType::Monthly)
            ->setParameter('quarterly', CalendarEventType::Quarterly)
            ->setParameter('yearly', CalendarEventType::Yearly)
            ->setParameter('onetimeDate', $date->format('Y-m-%'))
            ->setParameter('yearlyDate', $date->format('%-m-%'))
            ->setParameter('quarterlyDate1', $date->format('%-m-%'))
            ->setParameter('quarterlyDate2', $date2->format('%-m-%'))
            ->setParameter('quarterlyDate3', $date3->format('%-m-%'))
            ->setParameter('quarterlyDate4', $date4->format('%-m-%'))
            ->orderBy('day(ce.date)', 'ASC')
            ->addOrderBy('ce.updatedAt', 'DESC');

        return $qb->getQuery()->getResult();
    }
}

//quaterly asi udělat 4 podmínky mod 12