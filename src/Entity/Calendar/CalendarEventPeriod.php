<?php

namespace App\Entity\Calendar;

enum CalendarEventPeriod: string
{
    case Daily = 'daily';
    case Monthly = 'monthly';
    case Quarterly = 'quarterly';
    case Yearly = 'yearly';
}