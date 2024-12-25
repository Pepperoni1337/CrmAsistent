<?php

namespace App\Controller\DailyNote;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

final class DailyNotesAction extends AbstractController
{
    public function __construct()
    {
    }

    #[Route('/daily-notes', name: 'daily_notes', methods: ['GET'])]
    public function __invoke()
    {
        return $this->render('daily_notes/daily_notes.html.twig');
    }
}