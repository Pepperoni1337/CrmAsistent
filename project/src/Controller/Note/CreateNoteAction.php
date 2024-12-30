<?php

namespace App\Controller\Note;

use App\Entity\Note\Note;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class CreateNoteAction extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em
    )
    {
    }

    #[Route('/notes/create', name: 'note_create', methods: ['GET', 'POST'])]
    public function __invoke(Request $request)
    {
        if ($request->getMethod() === 'POST') {
            $entity = new Note(
                text: $request->request->get('text'),
            );

            $this->em->persist($entity);
            $this->em->flush();

            return $this->redirectToRoute('note_list');
        }

        return $this->render(
            'note/create_note.html.twig',
            [],
        );
    }
}