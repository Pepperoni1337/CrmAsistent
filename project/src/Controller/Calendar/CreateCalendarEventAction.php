<?php

namespace App\Controller\Calendar;

use App\Entity\Calendar\CalendarEvent;
use App\Entity\Calendar\CalendarEventType;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class CreateCalendarEventAction extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em
    )
    {
    }

    #[Route('/calendar/create_event', name: 'calendar_event_create', methods: ['GET', 'POST'])]
    public function __invoke(Request $request)
    {
        if ($request->getMethod() === 'POST') {

            $date = new DateTimeImmutable($request->get('date'));

            $type = match ($request->get('type')) {
                'one-time' => CalendarEventType::OneTime,
                'monthly' => CalendarEventType::Monthly,
                'quarterly' => CalendarEventType::Quarterly,
                'yearly' => CalendarEventType::Yearly,
            };

            $entity = new CalendarEvent(
                name: $request->request->get('name'),
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