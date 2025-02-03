<?php

namespace App\Service;

use App\Entity\Project\Project;
use Symfony\Component\HttpFoundation\RequestStack;

final class CurrentProjectPersister
{
    public const PROJECT_ID = 'project_id';

    public function __construct(
        RequestStack $requestStack,
    ) {
        $this->session = $requestStack->getSession();
    }

    public function persist(?Project $project): void
    {
        if ($project === null) {
            $this->session->remove(self::PROJECT_ID);

            return;
        }

        $this->session->set(self::PROJECT_ID, $project->getId()->jsonSerialize());
    }
}