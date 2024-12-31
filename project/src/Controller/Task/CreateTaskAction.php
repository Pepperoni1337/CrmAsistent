<?php

namespace App\Controller\Task;

use App\Entity\Task\Task;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

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
            $entity = new Task(
                name: $request->request->get('name'),
                description: $request->request->get('description'),
            );

            $this->em->persist($entity);
            $this->em->flush();

            return $this->redirectToRoute('task_list');
        }

        return $this->render(
            'task/create_task.html.twig',
            [],
        );
    }
}