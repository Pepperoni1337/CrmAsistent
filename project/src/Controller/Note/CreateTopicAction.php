<?php

namespace App\Controller\Note;

use App\Entity\Note\Topic;
use App\Entity\Project\Project;
use App\Service\CurrentProjectPersister;
use App\Service\CurrentProjectProvider;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

final class CreateTopicAction extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly CurrentProjectProvider $currentProjectProvider,
        private readonly CurrentProjectPersister $currentProjectPersister,
    ) {

    }

    #[Route('/notes/create-topic', name: 'topic_create', methods: ['GET', 'POST'])]
    public function __invoke(Request $request): Response
    {
        if ($request->getMethod() === 'POST') {
            $project = $this->getProject($request->request->get(Topic::PROJECT));
            $this->currentProjectPersister->persist($project);

            $entity = new Topic(
                project: $project,
                name: $request->request->get(Topic::NAME),
                description: $request->request->get(Topic::DESCRIPTION),
            );

            $this->em->persist($entity);
            $this->em->flush();

            return $this->redirectToRoute('note_topic_list');
        }

        return $this->render(
            'note/create_topic.html.twig',
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