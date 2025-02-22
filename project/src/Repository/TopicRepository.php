<?php

namespace App\Repository;

use App\Entity\DailyNote\DailyNote;
use App\Entity\Note\Topic;
use App\Entity\Project\Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DailyNote>
 */
final class TopicRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Topic::class);
    }

    public function findByProject(?Project $project): array
    {
        $qb = $this->createQueryBuilder('t');
        if ($project !== null) {
            $qb->andWhere('t.project = :project');
            $qb->setParameter('project', $project->getId(), 'uuid');
        } else {
            $qb->leftJoin('t.project', 'project');
            $qb->andWhere('project.private = :private');
            $qb->setParameter('private', false);
        }
        $qb->orderBy('t.updatedAt', 'DESC');

        return $qb->getQuery()->getResult();
    }
}