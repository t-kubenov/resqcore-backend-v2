<?php

namespace App\Document\Traits;

use DateTimeInterface;

trait AltersDisplayedDates
{
    protected function serializeDate(DateTimeInterface $date): string
    {
        $formatted = $date->hour || $date->minute
            ? $date->format('d.m.Y H:i')
            : $date->format('d.m.Y');

        return $formatted;
    }
}
