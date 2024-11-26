<?php

namespace App\Traits;

use DateTimeInterface;

trait AltersDisplayedDates
{
    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format(config('app.date_format')); // Customize the format as needed
    }
}
