<?php

namespace App\Controller;

use App\Entity\BankAccount\BankTransaction;
use App\Entity\BankAccount\BankTransactionStatus;
use App\Entity\BankAccount\BankTransactionType;
use App\Entity\Calendar\CalendarEvent;
use App\Entity\Calendar\CalendarEventType;
use App\Entity\DailyNote\DailyNote;
use App\Entity\Note\Note;
use App\Entity\Note\Topic;
use App\Entity\Project\Project;
use App\Entity\Task\Task;
use App\Repository\CalendarEventRepository;
use App\Service\FileGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/test')]
final class TestController extends AbstractController
{
    private const FILES = [
        [
            'path' => __DIR__ . '/../Controller/Note/',
            'template' => 'code_generator/create_controller.html.twig',
            'fileName' => 'CreateNoteAction',
            'routePath' => '/notes/create',
            'routeName' => 'note_create',
            'entity' => Note::class,
            'redirectRouteName' => 'note_topic_list',
            'templatePath' => 'note/create_note.html.twig',
            'fields' => ['text'],
            'folder' => 'Note',
        ],
        [
            'path' => __DIR__ . '/../Controller/DailyNote/',
            'template' => 'code_generator/create_controller.html.twig',
            'fileName' => 'CreateDailyNoteAction',
            'routePath' => '/daily_notes/create',
            'routeName' => 'daily_note_create',
            'entity' => DailyNote::class,
            'redirectRouteName' => 'daily_note_list',
            'templatePath' => 'daily_note/create_daily_note.html.twig',
            'fields' => ['text'],
            'folder' => 'DailyNote',
        ],
        [
            'path' => __DIR__ . '/../Controller/Calendar/',
            'template' => 'code_generator/create_controller.html.twig',
            'fileName' => 'CreateCalendarEventAction',
            'routePath' => '/calendar_events/create',
            'routeName' => 'calendar_event_create',
            'entity' => CalendarEvent::class,
            'redirectRouteName' => 'calendar_event_list',
            'templatePath' => 'calendar/create_calendar_event.html.twig',
            'fields' => ['name', 'day', 'month', 'year'],
            'folder' => 'Calendar',
        ],
        [
            'path' => __DIR__ . '/../Controller/Task/',
            'template' => 'code_generator/create_controller.html.twig',
            'fileName' => 'CreateTaskAction',
            'routePath' => '/tasks/create',
            'routeName' => 'task_create',
            'entity' => Task::class,
            'redirectRouteName' => 'task_list',
            'templatePath' => 'task/create_task.html.twig',
            'fields' => ['name', 'description',],
            'folder' => 'Task',
        ],
    ];

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        //private readonly FileGenerator $fileGenerator,

    ) {
    }

    public function __invoke(): Response
    {
        $project = $this->entityManager->getRepository(Project::class)->findOneBy([
            'name' => 'Práce DS'
        ]);


        $topics = $this->entityManager->getRepository(Topic::class)->findAll();

        $notes = $this->entityManager->getRepository(Note::class)->findBy([Note::PROJECT => $project]);
        $topic = $topics[1];

        foreach ($notes as $note) {
            $note->setTopic($topic);
        }

        $this->entityManager->flush();

/*
        $test = $this->entityManager->getRepository(Task::class)->findAll();

        foreach ($test as $t) {
            //$t->setProject($project);
            $this->entityManager->persist($t);
        }
        $this->entityManager->flush();

        dd($test);
/*
        foreach (self::FILES as $file) {
            $this->fileGenerator->generate(
                $file['path'],
                $file['template'],
                [
                    'fileName' => $file['fileName'],
                    'routePath' => $file['routePath'],
                    'routeName' => $file['routeName'],
                    'entity' => $file['entity'],
                    'redirectRouteName' => $file['redirectRouteName'],
                    'templatePath' => $file['templatePath'],
                    'fields' => $file['fields'],
                    'folder' => $file['folder'],
                ],
            );
        }
*/
        return new JsonResponse([
            'message' => 'Hello world!',
        ]);
    }
}