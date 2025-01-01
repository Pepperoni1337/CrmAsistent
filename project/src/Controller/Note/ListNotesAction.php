<?php

namespace App\Controller\Note;

use App\Entity\Note\Note;
use App\Entity\Project\Project;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

final class ListNotesAction extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em
    )
    {
    }

    #[Route('/notes', name: 'note_list', methods: ['GET'])]
    public function __invoke(Request $request): Response
    {
        $repository = $this->em->getRepository(Note::class);

        $params = [];

        $projectId = $request->get('project_id');
        if ($projectId !== null && $projectId !== '') {
            $uuid = Uuid::fromString($projectId);
            $project = $this->em->getRepository(Project::class)->find($uuid);

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