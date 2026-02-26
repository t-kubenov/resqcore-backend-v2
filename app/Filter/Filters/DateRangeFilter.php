<?php

namespace App\Filter\Filters;

use Illuminate\Database\Eloquent\Builder;

class DateRangeFilter extends BaseFilter
{
    public function apply(Builder $query, mixed $value): void
    {
        $dates = $this->splitDates($value);

        if ($dates['from']) {
            $query->whereDate($this->column, '>=', $dates['from']);
        }
        if ($dates['to']) {
            $query->whereDate($this->column, '<=', $dates['to']);
        }
    }

    private function splitDates($string): array
    {
        [$result['from'], $result['to']] = explode('|', $string);

        return $result;
    }
}
