<?php

namespace App\Entity\Calendar;

enum CalendarEventType: string
{
    case OneTime = 'one_time';
    case Recurring = 'recurring';
}