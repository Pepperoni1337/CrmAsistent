<?php

namespace App\Entity\Calendar;

use App\Repository\CalendarEventRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: CalendarEventRepository::class)]
#[ORM\HasLifecycleCallbacks]
final class CalendarEvent
{
    public const ID = 'id';
    public const NAME = 'name';
    public const DAY = 'day';
    public const MONTH = 'month';
    public const YEAR = 'year';
    public const TYPE = 'type';
    public const UPDATED_AT = 'updatedAt';

    #[ORM\Id]
    #[ORM\Column(type: 'uuid')]
    private Uuid $id;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $name;

    #[ORM\Column(type: Types::INTEGER)]
    private int $day;

    #[ORM\Column(type: Types::INTEGER)]
    private int $month;

    #[ORM\Column(type: Types::INTEGER)]
    private int $year;

    #[ORM\Column(type: Types::STRING, length: 255, enumType: CalendarEventType::class)]
    private CalendarEventType $type;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, enumType: CalendarEventPeriod::class)]
    private ?CalendarEventPeriod $period = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private DateTimeImmutable $updatedAt;

    public function __construct(
        string $name,
        int $day,
        int $month,
        int $year,
        CalendarEventType $type
    ) {
        $this->id = Uuid::v6();
        $this->name = $name;
        $this->day = $day;
        $this->month = $month;
        $this->year = $year;
        $this->type = $type;
        $this->updatedAt = new DateTimeImmutable();
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

    public function getDay(): int
    {
        return $this->day;
    }

    public function setDay(int $day): void
    {
        $this->day = $day;
    }

    public function getMonth(): int
    {
        return $this->month;
    }

    public function setMonth(int $month): void
    {
        $this->month = $month;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function setYear(int $year): void
    {
        $this->year = $year;
    }

    public function getType(): CalendarEventType
    {
        return $this->type;
    }

    public function setType(CalendarEventType $type): void
    {
        $this->type = $type;
    }

    public function getPeriod(): ?CalendarEventPeriod
    {
        return $this->period;
    }

    public function setPeriod(?CalendarEventPeriod $period): void
    {
        $this->period = $period;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }
}