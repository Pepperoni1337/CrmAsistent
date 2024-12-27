<?php

namespace App\Controller\Calendar;

use App\Repository\CalendarEventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CalendarAction extends AbstractController
{
    public function __construct(
        private readonly CalendarEventRepository $repository,
    ) {
    }

    #[Route('/calendar', name: 'calendar', methods: ['GET'])]
    public function __invoke(): Response
    {
        $thisMonthEvents = $this->repository->findThisMonthEvents();

        $nextMonthEvents = $this->repository->findNextMonthEvents();

        return $this->render(
            'calendar/calendar.html.twig',
            [
                'thisMonthEvents' => $thisMonthEvents,
                'nextMonthEvents' => $nextMonthEvents,
            ],
        );
    }
}