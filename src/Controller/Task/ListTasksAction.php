<?php

namespace App\Controller\Task;

use App\Entity\Task\Task;
use App\Entity\Task\TaskStatus;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ListTasksAction extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em
    )
    {
    }

    #[Route('/', methods: ['GET'])]
    #[Route('/tasks', name: 'task_list', methods: ['GET'])]
    public function __invoke(): Response
    {
        $repository = $this->em->getRepository(Task::class);

        $backlogTasks = $repository->findBy(['status' => TaskStatus::Backlog], [Task::UPDATED_AT => 'DESC']);
        $inProgressTasks = $repository->findBy(['status' => TaskStatus::InProgress], [Task::UPDATED_AT => 'DESC']);
        $doneTasks = $repository->findBy(['status' => TaskStatus::Done], [Task::UPDATED_AT => 'DESC']);

        return $this->render(
            'task/list_tasks.html.twig',
            [
                'backlogTasks' => $backlogTasks,
                'inProgressTasks' => $inProgressTasks,
                'doneTasks' => $doneTasks,
            ],
        );
    }
}