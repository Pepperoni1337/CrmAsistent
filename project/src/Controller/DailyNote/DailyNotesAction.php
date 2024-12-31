<?php

namespace App\Controller\DailyNote;

use App\Entity\DailyNote\DailyNote;
use App\Repository\DailyNoteRepository;
use App\Utils\DateTimeUtils;
use DateTimeImmutable;
use DateTimeInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DailyNotesAction extends AbstractController
{
    public function __construct(
        private readonly DailyNoteRepository $repository
    )
    {
    }

    #[Route('/daily-notes', name: 'daily_notes', methods: ['GET'])]
    public function __invoke(Request $request): Response
    {
        $offset = $request->query->getInt('offset', 0);


        $date = new DateTimeImmutable('now');

        $date1 = DateTimeUtils::offsetDay($date, $offset - 1);
        $date2 = DateTimeUtils::offsetDay($date, $offset);
        $date3 = DateTimeUtils::offsetDay($date, $offset + 1);

        return $this->render(
            'daily_note/daily_notes.html.twig',
            [
                'offset' => $offset,
                'daily_notes' => [
                    $this->getDayData($date1),
                    $this->getDayData($date2),
                    $this->getDayData($date3),
                ],
            ],
        );
    }

    /**
     * @return array<string, DateTimeInterface|DailyNote[]>
     */
    private function getDayData(DateTimeInterface $date): array
    {
        $daily_notes = $this->repository->findByDay($date);

        return [
            'date' => $date,
            'daily_notes' => $daily_notes,
        ];
    }
}