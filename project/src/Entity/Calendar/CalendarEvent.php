<?php

namespace App\Entity\Calendar;

use App\Repository\CalendarEventRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: CalendarEventRepository::class)]
#[ORM\HasLifecycleCallbacks]
class CalendarEvent
{
    public const ID = 'id';
    public const NAME = 'name';
    public const DATE = 'date';
    public const TYPE = 'type';
    public const UPDATED_AT = 'updatedAt';

    #[ORM\Id]
    #[ORM\Column(type: 'uuid')]
    private Uuid $id;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $name;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private DateTimeImmutable $date;

    #[ORM\Column(type: Types::STRING, length: 255, enumType: CalendarEventType::class)]
    private CalendarEventType $type;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private DateTimeImmutable $updatedAt;

    public function __construct(
        string $name,
        DateTimeImmutable $date,
        CalendarEventType $type
    ) {
        $this->id = Uuid::v7();
        $this->name = $name;
        $this->date = $date;
        $this->type = $type;
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

    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(DateTimeImmutable $date): void
    {
        $this->date = $date;
    }

    public function getType(): CalendarEventType
    {
        return $this->type;
    }

    public function setType(CalendarEventType $type): void
    {
        $this->type = $type;
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