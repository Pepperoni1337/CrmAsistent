<?php

namespace App\Controller\Calendar;

use App\Entity\Calendar\CalendarEvent;
use App\Entity\Calendar\CalendarEventType;
use DateTime;
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

            $date = new DateTime($request->get('date'));

            $entity = new CalendarEvent(
                name: $request->request->get('name'),
                day: $date->format('d'),
                month: $date->format('m'),
                year: $date->format('Y'),
                type: CalendarEventType::OneTime,
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