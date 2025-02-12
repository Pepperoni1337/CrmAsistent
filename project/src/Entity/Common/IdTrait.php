<?php

namespace App\Entity\Common;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

trait IdTrait
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid')]
    private Uuid $id;

    public function getId(): Uuid
    {
        return $this->id;
    }
}