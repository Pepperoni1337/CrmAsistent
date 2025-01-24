<?php

namespace App\Controller\Task;

use App\Entity\Project\Project;
use App\Entity\Task\Task;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/tasks/{task}}', name: 'task_detail', methods: ['GET', 'POST'])]
final class TaskDetailAction extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em
    ) {
    }

    public function __invoke(Task $task, Request $request): Response
    {
        if ($request->getMethod() === 'POST') {
            $this->mapRequestToTask($request, $task);
            $this->em->flush();
        }

        return $this->render(
            'task/detail.html.twig',
            [
                'task' => $task,
                'projects' => $this->em->getRepository(Project::class)->findAll(),
            ],
        );
    }

    private function mapRequestToTask(Request $request, Task $task): Task
    {
        $task->setName($request->request->get(Task::NAME));
        $task->setDescription($request->request->get(Task::DESCRIPTION));
        $task->setProject($this->em->getRepository(Project::class)->find($request->request->get(Task::PROJECT)));
        $task->setDifficulty((int) $request->request->get(Task::DIFFICULTY));

        return $task;
    }
}