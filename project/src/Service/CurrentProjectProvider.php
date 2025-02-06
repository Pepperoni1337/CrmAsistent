<?php

namespace App\Service;

use App\Entity\Project\Project;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Uid\Uuid;

final class CurrentProjectProvider
{
    private SessionInterface $session;

    public function __construct(
        RequestStack $requestStack,
        private readonly EntityManagerInterface $em
    ) {
        $this->session = $requestStack->getSession();
    }

    public function getProject(?string $requestProjectId = null): ?Project
    {
        if ($requestProjectId === "") {
            return null;
        }

        $stringId = $requestProjectId ?? $this->session->get(CurrentProjectPersister::PROJECT_ID);

        if ($stringId === null) {
            return null;
        }

        $id = Uuid::fromString($stringId);

        return $this->em->find(Project::class, $id);
    }
}