<?php

namespace App\Services\Filters\Filters;

use Illuminate\Database\Eloquent\Builder;

class WhereInFilter extends BaseFilter
{
    public function apply(Builder $query, mixed $value): void
    {
        $query->whereIn($this->column, $value);
    }
}
