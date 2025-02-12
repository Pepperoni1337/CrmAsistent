<?php

namespace App\Entity\Task;

use App\Entity\Common\IdTrait;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class TaskComment
{
    use IdTrait;

    public const ID = 'id';
    public const TASK = 'task';
    public const TEXT = 'text';
    public const CREATED_AT = 'createdAt';
    public const UPDATED_AT = 'updatedAt';

    #[ORM\ManyToOne(targetEntity: Task::class, cascade: ['remove'], inversedBy: Task::COMMENTS)]
    #[ORM\JoinColumn(nullable: false)]
    private Task $task;

    #[ORM\Column(type: Types::TEXT, length: 2047)]
    private string $text;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private DateTimeImmutable $createdAt;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private DateTimeImmutable $updatedAt;

    public function __construct(
        Task $task,
        string $text,
    ) {
        $this->id = Uuid::v7();
        $this->task = $task;
        $this->text = $text;
        $this->createdAt = new DateTimeImmutable();
    }

    public function getTask(): Task
    {
        return $this->task;
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

    #[ORM\PreUpdate]
    #[ORM\PrePersist]
    public function updateTimestamp(): void
    {
        $this->updatedAt = new DateTimeImmutable();
    }
}