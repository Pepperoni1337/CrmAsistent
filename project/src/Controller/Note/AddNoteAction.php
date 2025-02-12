<?php

namespace App\Controller\Note;

use App\Entity\Note\Note;
use App\Entity\Note\Topic;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AddNoteAction extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em,
    )
    {
    }

    #[Route('/topics/{topic}/add-note', name: 'topic_add_note', methods: ['POST'])]
    public function __invoke(Topic $topic, Request $request): Response
    {

        $entity = new Note(
            text: $request->request->get(Note::TEXT),
            topic: $topic,
        );

        $this->em->persist($entity);
        $this->em->flush();

        return $this->redirectToRoute(
            'note_topic_detail',
            [
                Note::TOPIC => $topic->getId(),
            ]
        );
    }
}