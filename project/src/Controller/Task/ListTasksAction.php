<?php

namespace App\Controller\Task;

use App\Entity\Project\Project;
use App\Entity\Task\TaskStatus;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

final class ListTasksAction extends AbstractController
{
    public function __construct(
        private readonly TaskRepository $repository,
        private readonly EntityManagerInterface $em
    )
    {
    }

    #[Route('/', methods: ['GET'])]
    #[Route('/tasks', name: 'task_list', methods: ['GET'])]
    public function __invoke(Request $request): Response
    {
        $projectId = $request->get('project_id');
        if ($projectId !== null && $projectId !== '') {
            $uuid = Uuid::fromString($projectId);
            $project = $this->em->getRepository(Project::class)->find($uuid);
        }

        $backlogTasks = $this->repository->findByStatusAndProject(TaskStatus::Backlog, $project ?? null);
        $inProgressTasks = $this->repository->findByStatusAndProject(TaskStatus::InProgress, $project ?? null);
        $doneTasks = $this->repository->findByStatusAndProject(TaskStatus::Done, $project ?? null);

        return $this->render(
            'task/list_tasks.html.twig',
            [
                'backlogTasks' => $backlogTasks,
                'inProgressTasks' => $inProgressTasks,
                'doneTasks' => $doneTasks,
                'project' => $project ?? null,
                'projects' => $this->em->getRepository(Project::class)->findAll(),
            ],
        );
    }
}