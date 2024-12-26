<?php

namespace App\Controller;

use App\Entity\DailyNote\DailyNote;
use App\Entity\Note\Note;
use App\Entity\Task\Task;
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
            'redirectRouteName' => 'note_list',
            'templatePath' => 'note/create_note.html.twig',
            'fields' => ['text'],
            'folder' => 'Note'
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
            'folder' => 'DailyNote'
        ]
    ];

    public function __construct(
        //private readonly EntityManagerInterface $entityManager,
        private readonly FileGenerator $fileGenerator,

    ) {
    }

    public function __invoke(): Response
    {
        /*
        $task = new Note('poznámka as ada a ' . random_int(1,50));
        $this->entityManager->persist($task);
        $this->entityManager->flush();

        $test = $this->entityManager->getRepository(Note::class)->findAll();

        dd($test);
*/

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
                ],
            );
        }

        return new JsonResponse([
            'message' => 'Hello world!',
        ]);
    }
}