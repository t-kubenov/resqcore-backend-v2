<?php

namespace App\Filter\Filters;

use Illuminate\Database\Eloquent\Builder;

class LikeFilter extends BaseFilter
{
    public function apply(Builder $query, mixed $value): void
    {
        $query->where($this->column, 'ILIKE', "%$value%");
    }
}
