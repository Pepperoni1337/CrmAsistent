<?php

namespace App\Entity\Calendar;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

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

    #[ORM\Column(type: Types::STRING,  length: 255)]
    private string $type;
}