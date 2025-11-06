<?php

namespace App\Services\Filters\Filters;

use Illuminate\Database\Eloquent\Builder;

class WhereFilter extends BaseFilter
{
    public function apply(Builder $query, mixed $value): void
    {
        $query->where($this->column, $value);
    }
}
