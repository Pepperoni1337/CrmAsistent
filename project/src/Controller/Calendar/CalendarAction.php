<?php

namespace App\Controller\Calendar;

use App\Entity\Calendar\CalendarEvent;
use App\Repository\CalendarEventRepository;
use App\Utils\DateTimeUtils;
use DateTimeImmutable;
use DateTimeInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CalendarAction extends AbstractController
{
    public function __construct(
        private readonly CalendarEventRepository $repository,
    ) {
    }

    #[Route('/calendar', name: 'calendar', methods: ['GET'])]
    public function __invoke(Request $request): Response
    {
        $offset = $request->query->getInt('offset', 0);

        $date = new DateTimeImmutable('first day of this month');

        $date1 = DateTimeUtils::offsetMonth($date, $offset - 1);
        $date2 = DateTimeUtils::offsetMonth($date, $offset);
        $date3 = DateTimeUtils::offsetMonth($date, $offset + 1);
        return $this->render(
            'calendar/calendar.html.twig',
            [
                'offset' => $offset,
                'events' => [
                    $this->getMonthData($date1),
                    $this->getMonthData($date2),
                    $this->getMonthData($date3),
                ],
            ],
        );
    }

    /**
     * @return array<string, string|CalendarEvent[]>
     */
    private function getMonthData(DateTimeInterface $date): array
    {
        $events = $this->repository->findByMonth($date);

        return [
            'month' => $date->format('m'),
            'year' => $date->format('Y'),
            'events' => $events,
        ];
    }
}