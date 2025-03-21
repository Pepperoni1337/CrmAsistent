<?php

namespace App\Entity\Note;

use App\Entity\Common\IdTrait;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class Note
{
    use IdTrait;

    public const ID = 'id';
    public const TOPIC = 'topic';
    public const TEXT = 'text';
    public const COMMENTS = 'comments';
    public const CREATED_AT = 'createdAt';
    public const UPDATED_AT = 'updatedAt';

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private Topic $topic;

    #[ORM\Column(type: 'string', length: 4095)]
    private string $text;

    /**
     * @var Collection<int, NoteComment>
     */
    #[ORM\OneToMany(targetEntity: NoteComment::class, mappedBy: NoteComment::NOTE)]
    private Collection $comments;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private DateTimeImmutable $createdAt;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private DateTimeImmutable $updatedAt;

    public function __construct(
        string $text,
        Topic $topic
    ) {
        $this->id = Uuid::v7();
        $this->text = $text;
        $this->topic = $topic;
        $this->comments = new ArrayCollection();
        $this->createdAt = new DateTimeImmutable();
    }

    public function getTopic(): Topic
    {
        return $this->topic;
    }

    public function setTopic(Topic $topic): void
    {
        $this->topic = $topic;
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

    /**
     * @return Collection<int, NoteComment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    #[ORM\PreUpdate]
    #[ORM\PrePersist]
    public function updateTimestamp(): void
    {
        $this->updatedAt = new DateTimeImmutable();
    }
}