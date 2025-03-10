<?php

namespace App\Entity\DailyNote;

use App\Entity\Common\IdTrait;
use App\Entity\Project\Project;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class DailyNote
{
    use IdTrait;

    public const ID = 'id';
    public const TEXT = 'text';
    public const CREATED_AT = 'createdAt';
    public const UPDATED_AT = 'updatedAt';
    public const PROJECT = 'project';

    #[ORM\Column(type: 'string', length: 2047)]
    private string $text;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private DateTimeImmutable $createdAt;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private DateTimeImmutable $updatedAt;

    #[ORM\ManyToOne(targetEntity: Project::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Project $project;

    public function __construct(
        string $text,
        Project $project

    ) {
        $this->id = Uuid::v7();
        $this->text = $text;
        $this->project = $project;
        $this->createdAt = new DateTimeImmutable();
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): void
    {
        $this->text = $text;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function getProject(): Project
    {
        return $this->project;
    }

    public function setProject(Project $project): void
    {
        $this->project = $project;
    }

    #[ORM\PreUpdate]
    #[ORM\PrePersist]
    public function updateTimestamp(): void
    {
        $this->updatedAt = new DateTimeImmutable();
    }
}