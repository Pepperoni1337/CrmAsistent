<?php

namespace App\Controller\Project;

use App\Entity\Project\Project;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CreateProjectAction extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em
    )
    {
    }

    #[Route('/projects/create', name: 'project_create', methods: ['GET', 'POST'])]
    public function __invoke(Request $request): Response
    {
        if ($request->getMethod() === 'POST') {
            $entity = new Project(
                name: $request->request->get(Project::NAME),
                taskPrefix: $request->request->get(Project::TASK_PREFIX),
                nextTaskId: (int) $request->request->get(Project::NEXT_TASK_ID),
                private: (bool) $request->request->get(Project::PRIVATE),
            );

            $this->em->persist($entity);
            $this->em->flush();

            return $this->redirectToRoute('project_list');
        }

        return $this->render(
            'project/create_project.html.twig',
            [],
        );
    }
}