<?php

namespace App\Entity\Note;

use App\Entity\Common\IdTrait;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
final class NoteComment
{
    use IdTrait;

    public const ID = 'id';
    public const NOTE = 'note';
    public const TEXT = 'text';
    public const CREATED_AT = 'createdAt';
    public const UPDATED_AT = 'updatedAt';

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private Note $note;

    #[ORM\Column(type: 'string', length: 4095)]
    private string $text;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private DateTimeImmutable $createdAt;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private DateTimeImmutable $updatedAt;

    public function __construct(
        string $text,
        Note $note
    ) {
        $this->id = Uuid::v7();
        $this->text = $text;
        $this->note = $note;
        $this->createdAt = new DateTimeImmutable();
    }

    public function getNote(): Note
    {
        return $this->note;
    }

    public function setNote(Note $note): void
    {
        $this->note = $note;
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