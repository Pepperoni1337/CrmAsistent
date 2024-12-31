<?php

namespace App\Controller\DailyNote;

use App\Entity\DailyNote\DailyNote;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

final class DailyNotesAction extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em
    )
    {
    }

    #[Route('/daily-notes', name: 'daily_notes', methods: ['GET'])]
    public function __invoke()
    {
        return $this->render(
            'daily_note/daily_notes.html.twig',
            [
                'daily_notes' => $this->em->getRepository(DailyNote::class)->findAll(),
            ]
        );
    }
}