<?php

namespace App\Entity\Task;

use App\Entity\Project\Project;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class Task
{
    public const ID = 'id';
    public const NAME = 'name';
    public const DESCRIPTION = 'description';
    public const STATUS = 'status';
    public const PROJECT = 'project';
    public const DIFFICULTY = 'difficulty';
    public const COMMENTS = 'comments';
    public const CREATED_AT = 'createdAt';
    public const UPDATED_AT = 'updatedAt';

    #[ORM\Id]
    #[ORM\Column(type: 'uuid')]
    private Uuid $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\Column(type: 'text')]
    private string $description;

    #[ORM\Column(type: Types::STRING, enumType: TaskStatus::class)]
    private TaskStatus $status = TaskStatus::Backlog;

    #[ORM\ManyToOne(targetEntity: Project::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Project $project;

    #[ORM\Column(type: Types::INTEGER, options: ['default' => 0])]
    private int $difficulty;

    #[ORM\OneToMany(targetEntity: TaskComment::class, mappedBy: TaskComment::TASK)]
    private Collection $comments;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private DateTimeImmutable $createdAt;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private DateTimeImmutable $updatedAt;

    public function __construct(
        string $name,
        string $description,
        Project $project,
        int $difficulty
    ) {
        $this->id = Uuid::v7();
        $this->name = $name;
        $this->description = $description;
        $this->project = $project;
        $this->difficulty = $difficulty;
        $this->comments = new ArrayCollection();
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

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getStatus(): TaskStatus
    {
        return $this->status;
    }

    public function setStatus(TaskStatus $status): void
    {
        $this->status = $status;
    }

    public function getProject(): Project
    {
        return $this->project;
    }

    public function setProject(Project $project): void
    {
        $this->project = $project;
    }

    public function getDifficulty(): int
    {
        return $this->difficulty;
    }

    public function setDifficulty(int $difficulty): void
    {
        $this->difficulty = $difficulty;
    }

    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(TaskComment $comment): void
    {
        $this->comments->add($comment);
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