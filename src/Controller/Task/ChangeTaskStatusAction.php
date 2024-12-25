<?php

namespace App\Controller\Task;

use App\Entity\Task\Task;
use App\Entity\Task\TaskStatus;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

final class ChangeTaskStatusAction extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em
    ) {
    }

    #[Route('/tasks/{id}/change_status/{status}', name: 'task_change_status', methods: ['GET'])]
    public function __invoke(string $id, string $status): RedirectResponse
    {
        $taskUUID = Uuid::fromString($id);

        $task = $this->em->find(Task::class, $taskUUID);

        if ($task === null) {
            throw $this->createNotFoundException('Task not found');
        }

        $statusEnum = match ($status) {
            'backlog' => TaskStatus::Backlog,
            'in_progress' => TaskStatus::InProgress,
            'done' => TaskStatus::Done,
            'deleted' => TaskStatus::Deleted,
            default => throw $this->createNotFoundException('Invalid status'),
        };

        $task->setStatus($statusEnum);

        $this->em->flush();

        return new RedirectResponse($this->generateUrl('task_list'));
    }
}