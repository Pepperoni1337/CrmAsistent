<?php

namespace App\Controller\Note;

use App\Entity\Note\Note;
use App\Entity\Note\Topic;
use App\Entity\Project\Project;
use App\Repository\TopicRepository;
use App\Service\CurrentProjectPersister;
use App\Service\CurrentProjectProvider;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

final class ListTopicsAction extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly CurrentProjectProvider $currentProjectProvider,
        private readonly CurrentProjectPersister $currentProjectPersister,
        private readonly TopicRepository $topicRepository,
    )
    {
    }

    #[Route('/notes', name: 'note_topic_list', methods: ['GET'])]
    public function __invoke(Request $request): Response
    {
        $project = $this->currentProjectProvider->getProject($request->get('project_id'));
        $this->currentProjectPersister->persist($project);

        return $this->render(
            'note/list_topics.html.twig',
            [
                'topics' => $this->topicRepository->findByProject($project),
                'project' => $project ?? null,
                'projects' => $this->em->getRepository(Project::class)->findAll(),
            ],
        );
    }
}