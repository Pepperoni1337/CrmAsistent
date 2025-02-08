<?php

namespace App\Controller\Note;

use App\Entity\Note\Topic;
use App\Entity\Project\Project;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/topics/{topic}}', name: 'note_topic_detail', methods: ['GET', 'POST'])]
final class TopicDetailAction extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em
    ) {
    }

    public function __invoke(Topic $topic, Request $request): Response
    {
        if ($request->getMethod() === 'POST') {
            $this->mapRequestToTopic($request, $topic);
            $this->em->flush();
        }

        return $this->render(
            'note/topic_detail.html.twig',
            [
                'topic' => $topic,
                'projects' => $this->em->getRepository(Project::class)->findAll(),
            ],
        );
    }

    private function mapRequestToTopic(Request $request, Topic $topic): void
    {
        $topic->setName($request->request->get(Topic::NAME));
        $topic->setDescription($request->request->get(Topic::DESCRIPTION));
        $topic->setProject($this->em->getRepository(Project::class)->find($request->request->get(Topic::PROJECT)));
    }
}