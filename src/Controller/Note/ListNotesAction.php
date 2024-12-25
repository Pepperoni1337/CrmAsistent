<?php

namespace App\Controller\Note;

use App\Entity\Note\Note;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ListNotesAction extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em
    )
    {
    }

    #[Route('/notes', name: 'note_list', methods: ['GET'])]
    public function __invoke(): Response
    {
        $repository = $this->em->getRepository(Note::class);

        $notes = $repository->findBy([], [Note::UPDATED_AT => 'DESC']);

        return $this->render(
            'note/list_notes.html.twig',
            [
                'notes' => $notes,
            ],
        );
    }
}