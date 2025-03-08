<?php

namespace App\Controller\Note;

use App\Entity\Note\Note;
use App\Entity\Note\NoteComment;
use App\Entity\Note\Topic;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CreateNoteCommentAction extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em,
    )
    {
    }

    #[Route('/topics/{topic}/notes/{note}/create-note-comment', name: 'topic_note_create_comment', methods: ['POST'])]
    public function __invoke(Topic $topic, Note $note, Request $request): Response
    {
        $comment = new NoteComment(
            text: $request->request->get(NoteComment::TEXT),
            note: $note,
        );

        $this->em->persist($comment);
        $this->em->flush();

        return $this->redirectToRoute(
            'note_topic_detail',
            [
                Note::TOPIC => $topic->getId(),
            ]
        );
    }
}