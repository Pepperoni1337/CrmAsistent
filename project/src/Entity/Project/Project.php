<?php

namespace App\Entity\Project;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class Project
{
    public const ID = 'id';
    public const NAME = 'name';
    public const TASK_PREFIX = 'taskPrefix';
    public const NEXT_TASK_ID = 'nextTaskId';
    public const UPDATED_AT = 'updatedAt';

    #[ORM\Id]
    #[ORM\Column(type: 'uuid')]
    private Uuid $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\Column(type: 'string', length: 255)]
    private string $taskPrefix;

    #[ORM\Column(type: 'integer')]
    private int $nextTaskId;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private DateTimeImmutable $createdAt;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private DateTimeImmutable $updatedAt;

    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => false])]
    private bool $private;

    public function __construct(
        string $name,
        string $taskPrefix,
        int $nextTaskId,
        bool $private,
    ) {
        $this->id = Uuid::v7();
        $this->name = $name;
        $this->taskPrefix = $taskPrefix;
        $this->nextTaskId = $nextTaskId;
        $this->private = $private;
        $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getTaskPrefix(): string
    {
        return $this->taskPrefix;
    }

    public function setTaskPrefix(string $taskPrefix): void
    {
        $this->taskPrefix = $taskPrefix;
    }

    public function getNextTaskId(): int
    {
        return $this->nextTaskId;
    }

    public function setNextTaskId(int $nextTaskId): void
    {
        $this->nextTaskId = $nextTaskId;
    }

    public function incrementNextTaskId(): void
    {
        $this->nextTaskId++;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function isPrivate(): bool
    {
        return $this->private;
    }

    public function setPrivate(bool $private): void
    {
        $this->private = $private;
    }

    #[ORM\PreUpdate]
    #[ORM\PrePersist]
    public function updateTimestamp(): void
    {
        $this->updatedAt = new DateTimeImmutable();
    }
}