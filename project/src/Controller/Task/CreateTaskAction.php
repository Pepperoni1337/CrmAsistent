<?php

namespace App\Controller\Task;

use App\Entity\Project\Project;
use App\Entity\Task\Task;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

final class CreateTaskAction extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em
    )
    {
    }

    #[Route('/tasks/create', name: 'task_create', methods: ['GET', 'POST'])]
    public function __invoke(Request $request): Response
    {
        if ($request->getMethod() === 'POST') {
            $project = $this->getProject($request->request->get(Task::PROJECT));

            $entity = new Task(
                name: $this->createNewTaskName(
                    name: $request->request->get(Task::NAME),
                    project: $project,
                ),
                description: $request->request->get(Task::DESCRIPTION),
                project: $project,
            );

            $project->incrementNextTaskId();
            $this->em->persist($entity);
            $this->em->persist($project);
            $this->em->flush();

            return $this->redirectToRoute('task_list');
        }

        return $this->render(
            'task/create_task.html.twig',
            [
                'projects' => $this->em->getRepository(Project::class)->findAll(),
            ],
        );
    }

    private function getProject(string $projectId): Project
    {
        $projectId = Uuid::fromString($projectId);

        return $this->em->getRepository(Project::class)->find($projectId);
    }

    private function createNewTaskName(string $name, Project $project): string
    {
        return $project->getTaskPrefix() . $project->getNextTaskId() . ' - ' . $name;
    }
}