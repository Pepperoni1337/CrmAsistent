<?php

namespace App\Repository;

use App\Entity\DailyNote\DailyNote;
use App\Entity\Project\Project;
use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DailyNote>
 */
final class DailyNoteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DailyNote::class);
    }

    /**
     * @return DailyNote[]
     */
    public function findByDayAndProject(DateTimeInterface $date, ?Project $project): array {
        $qb = $this->createQueryBuilder('dn');

        $qb->where(
            $qb->expr()->eq('date(dn.createdAt)', ':date')
        );
        $qb->setParameter('date', $date->format('Y-m-d'));

        if($project !== null) {
            $qb->andWhere('dn.project = :project')
                ->setParameter('project', $project->getId(), 'uuid');
        } else {
            $qb->leftJoin('dn.project', 'project');
            $qb->andWhere('project.private = :private')
                ->setParameter('private', false);
        }

        $qb->orderBy('dn.createdAt', 'DESC');

        return $qb->getQuery()->getResult();
    }

}