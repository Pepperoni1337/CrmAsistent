<?php

namespace App\Controller\Project;

use App\Entity\Project\Project;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ListProjectsAction extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em
    ) {
    }

    #[Route('/projects', name: 'project_list', methods: ['GET'])]
    public function __invoke(): Response
    {
        $repository = $this->em->getRepository(Project::class);

        $projects = $repository->findBy([], [Project::UPDATED_AT => 'DESC']);

        return $this->render(
            'project/list_projects.html.twig',
            [
                'projects' => $projects,
            ],
        );
    }
}