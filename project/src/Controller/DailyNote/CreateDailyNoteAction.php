<?php

namespace App\Controller\DailyNote;

use App\Entity\DailyNote\DailyNote;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CreateDailyNoteAction extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em
    )
    {
    }

    #[Route('/daily_notes/create', name: 'daily_note_create', methods: ['GET', 'POST'])]
    public function __invoke(Request $request): Response
    {
        if ($request->getMethod() === 'POST') {
            $entity = new DailyNote(
                text: $request->request->get('text'),
            );

            $this->em->persist($entity);
            $this->em->flush();

            return $this->redirectToRoute('daily_notes');
        }

        return $this->render(
            'daily_note/create_daily_note.html.twig',
            [],
        );
    }
}