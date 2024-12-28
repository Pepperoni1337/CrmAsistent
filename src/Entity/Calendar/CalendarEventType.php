<?php

namespace App\Entity\Calendar;

enum CalendarEventType: string
{
    case OneTime = 'one_time';
    case Monthly = 'monthly';
    case Quarterly = 'quarterly';
    case Yearly = 'yearly';
}