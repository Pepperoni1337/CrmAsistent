<?php

namespace App\Controller\Calendar;

use App\Entity\Calendar\CalendarEvent;
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

    #[Route('/calendar_events/create', name: 'calendar_event_create', methods: ['GET', 'POST'])]
    public function __invoke(Request $request)
    {
        if ($request->getMethod() === 'POST') {
            $entity = new CalendarEvent(
                name: $request->request->get('name'),
                day: $request->request->get('day'),
                month: $request->request->get('month'),
                year: $request->request->get('year'),
            );

            $this->em->persist($entity);
            $this->em->flush();

            return $this->redirectToRoute('calendar_event_list');
        }

        return $this->render(
            'calendar/create_calendar_event.html.twig',
            [],
        );
    }
}