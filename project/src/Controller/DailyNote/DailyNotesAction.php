<?php

namespace App\Controller\DailyNote;

use App\Entity\DailyNote\DailyNote;
use App\Entity\Project\Project;
use App\Repository\DailyNoteRepository;
use App\Utils\DateTimeUtils;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

final class DailyNotesAction extends AbstractController
{
    public function __construct(
        private readonly DailyNoteRepository $repository,
        private readonly EntityManagerInterface $em
    )
    {
    }

    #[Route('/daily-notes', name: 'daily_notes', methods: ['GET'])]
    public function __invoke(Request $request): Response
    {
        $offset = $request->query->getInt('offset', 0);

        $projectId = $request->get('project_id');
        $project = null;
        if ($projectId !== null && $projectId !== '') {
            $uuid = Uuid::fromString($projectId);
            /** @var ?Project $project */
            $project = $this->em->getRepository(Project::class)->find($uuid);
        }

        $date = new DateTimeImmutable('now');

        $date1 = DateTimeUtils::offsetDay($date, $offset - 1);
        $date2 = DateTimeUtils::offsetDay($date, $offset);
        $date3 = DateTimeUtils::offsetDay($date, $offset + 1);

        return $this->render(
            'daily_note/daily_notes.html.twig',
            [
                'offset' => $offset,
                'daily_notes' => [
                    $this->getDayData($date1, $project),
                    $this->getDayData($date2, $project),
                    $this->getDayData($date3, $project),
                ],
                'project' => $project ?? null,
                'projects' => $this->em->getRepository(Project::class)->findAll(),
            ],
        );
    }

    /**
     * @return array<string, DateTimeInterface|DailyNote[]>
     */
    private function getDayData(DateTimeInterface $date, ?Project $project = null): array
    {
        $daily_notes = $this->repository->findByDayAndProject($date, $project);

        return [
            'date' => $date,
            'daily_notes' => $daily_notes,
        ];
    }
}