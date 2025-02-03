<?php

namespace App\Controller\Note;

use App\Entity\Note\Note;
use App\Entity\Project\Project;
use App\Service\CurrentProjectPersister;
use App\Service\CurrentProjectProvider;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

final class ListNotesAction extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly CurrentProjectProvider $currentProjectProvider,
        private readonly CurrentProjectPersister $currentProjectPersister,
    )
    {
    }

    #[Route('/notes', name: 'note_list', methods: ['GET'])]
    public function __invoke(Request $request): Response
    {
        $project = $this->currentProjectProvider->getProject($request->get('project_id'));
        $this->currentProjectPersister->persist($project);

        $repository = $this->em->getRepository(Note::class);

        $params = [];

        if ($project !== null) {
            $params[Note::PROJECT] = $project;
        }

        $notes = $repository->findBy($params, [Note::UPDATED_AT => 'DESC']);

        return $this->render(
            'note/list_notes.html.twig',
            [
                'notes' => $notes,
                'project' => $project ?? null,
                'projects' => $this->em->getRepository(Project::class)->findAll(),
            ],
        );
    }
}