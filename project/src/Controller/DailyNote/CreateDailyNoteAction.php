<?php

namespace App\Controller\DailyNote;

use App\Entity\DailyNote\DailyNote;
use App\Entity\Project\Project;
use App\Service\CurrentProjectPersister;
use App\Service\CurrentProjectProvider;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

final class CreateDailyNoteAction extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly CurrentProjectProvider $currentProjectProvider,
        private readonly CurrentProjectPersister $currentProjectPersister,
    )
    {
    }

    #[Route('/daily_notes/create', name: 'daily_note_create', methods: ['GET', 'POST'])]
    public function __invoke(Request $request): Response
    {
        if ($request->getMethod() === 'POST') {
            $project = $this->getProject($request->request->get(DailyNote::PROJECT));
            $this->currentProjectPersister->persist($project);

            $entity = new DailyNote(
                text: $request->request->get(DailyNote::TEXT),
                project: $project,
            );

            $this->em->persist($entity);
            $this->em->flush();

            return $this->redirectToRoute('daily_notes');
        }

        return $this->render(
            'daily_note/create_daily_note.html.twig',
            [
                'projects' => $this->em->getRepository(Project::class)->findAll(),
                'currentProject' => $this->currentProjectProvider->getProject(),
            ],
        );
    }

    private function getProject(string $projectId): Project
    {
        $projectId = Uuid::fromString($projectId);

        return $this->em->getRepository(Project::class)->find($projectId);
    }
}