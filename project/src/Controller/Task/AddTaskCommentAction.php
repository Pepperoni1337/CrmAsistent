<?php

namespace App\Controller\Task;

use App\Entity\Task\Task;
use App\Entity\Task\TaskComment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/tasks/{task}/add_comment', name: 'task_add_comment', methods: ['GET', 'POST'])]
final class AddTaskCommentAction extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em
    ) {
    }

    public function __invoke(Task $task, Request $request): Response
    {
        $taskComment = new TaskComment(
            task: $task,
            text: $request->request->get(TaskComment::TEXT),
        );

        $this->em->persist($taskComment);
        $this->em->flush();

        return $this->redirectToRoute('task_detail', ['task' => $task->getId()]);
    }
}