<?php

namespace App\Controller\Calendar;

use App\Entity\Calendar\CalendarEvent;
use App\Entity\Calendar\CalendarEventType;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CreateCalendarEventAction extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em
    )
    {
    }

    #[Route('/calendar/create_event', name: 'calendar_event_create', methods: ['GET', 'POST'])]
    public function __invoke(Request $request): Response
    {
        if ($request->getMethod() === 'POST') {

            $date = new DateTimeImmutable($request->get(CalendarEvent::DATE));

            $type = match ($request->get(CalendarEvent::TYPE)) {
                'one_time' => CalendarEventType::OneTime,
                'monthly' => CalendarEventType::Monthly,
                'quarterly' => CalendarEventType::Quarterly,
                'yearly' => CalendarEventType::Yearly,
                default => throw new InvalidArgumentException('Invalid type'),
            };

            $entity = new CalendarEvent(
                name: $request->request->get(CalendarEvent::NAME),
                date: $date,
                type: $type,
            );

            $this->em->persist($entity);
            $this->em->flush();

            return $this->redirectToRoute('calendar');
        }

        return $this->render(
            'calendar/create_calendar_event.html.twig',
            [],
        );
    }
}