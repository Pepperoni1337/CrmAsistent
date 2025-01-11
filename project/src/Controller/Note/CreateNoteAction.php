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

final class CreateNoteAction extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em
    )
    {
    }

    #[Route('/notes/create', name: 'note_create', methods: ['GET', 'POST'])]
    public function __invoke(Request $request): Response
    {
        if ($request->getMethod() === 'POST') {
            $project = $this->getProject($request->request->get(Note::PROJECT));

            $entity = new Note(
                text: $request->request->get(Note::TEXT),
                project: $project,
            );

            $this->em->persist($entity);
            $this->em->flush();

            return $this->redirectToRoute('note_list');
        }

        return $this->render(
            'note/create_note.html.twig',
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
}