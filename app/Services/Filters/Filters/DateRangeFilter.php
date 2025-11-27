<?php

namespace App\Services\Filters\Filters;

use Illuminate\Database\Eloquent\Builder;

class DateRangeFilter extends BaseFilter
{
    public function apply(Builder $query, mixed $value): void
    {
        if (isset($value['from'])) {
            $query->whereDate($this->column, '>=', $value['from']);
        }
        if (isset($value['to'])) {
            $query->whereDate($this->column, '<=', $value['to']);
        }
    }
}
