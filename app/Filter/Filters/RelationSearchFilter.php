<?php

namespace App\Filter\Filters;

use App\Filter\Contracts\FilterStrategy;
use Illuminate\Database\Eloquent\Builder;

class RelationSearchFilter implements FilterStrategy
{
    public function __construct(
        protected string $relation,
        protected string $column
    ) {}

    public function apply(Builder $query, mixed $value): void
    {
        $query->whereHas($this->relation, function ($q) use ($value) {
            $q->where($this->column, 'ILIKE', "%$value%");
        });
    }
}
