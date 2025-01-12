<?php

namespace App\Repository;

use App\Entity\Project\Project;
use App\Entity\Task\Task;
use App\Entity\Task\TaskStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Task>
 */
final class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    public function findByStatusAndProject(TaskStatus $status, ?Project $project): array {
        $qb = $this->createQueryBuilder('t');

        $qb->where('t.status = :status')
            ->setParameter('status', $status);

        if($project !== null) {
            $qb->andWhere('t.project = :project')
                ->setParameter('project', $project->getId(), 'uuid');
        }

        $qb->orderBy('t.updatedAt', 'DESC');

        return $qb->getQuery()->getResult();
    }

}