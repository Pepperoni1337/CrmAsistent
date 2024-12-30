<?php

namespace App\Utils;

use DateTime;
use DateTimeInterface;

final class DateTimeUtils
{
    public static function offsetMonth(DateTimeInterface $date, int $offset): DateTimeInterface
    {
        $newDate = new DateTime($date->format('Y-m-d'));

        $newDate->modify(sprintf('%d month', $offset));

        return $newDate;
    }
}