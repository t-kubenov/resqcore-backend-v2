<?php

namespace App\Filter\Filters;

use App\Filter\Contracts\FilterStrategy;
use Illuminate\Database\Eloquent\Builder;

class SearchFilter implements FilterStrategy
{
    public function __construct(
        protected array $searchableFields = []
    ) {}

    public function apply(Builder $query, mixed $value): void
    {
        $query->where(function ($q) use ($value) {
            foreach ($this->searchableFields as $index => $field) {
                if (is_string($field)) {
                    $this->addColumnSearch($q, $field, $value, $index === 0);
                } elseif (is_array($field)) {
                    $this->addRelationSearch($q, $field, $value, $index === 0);
                }
            }
        });
    }

    protected function addColumnSearch(Builder $query, string $column, string $value, bool $isFirst): void
    {
        $query->{$isFirst ? 'where' : 'orWhere'}($column, 'ILIKE', "%{$value}%");
    }

    protected function addRelationSearch(Builder $query, array $field, string $value, bool $isFirst): void
    {
        $query->{$isFirst ? 'whereHas' : 'orWhereHas'}($field[0], function ($q) use ($field, $value) {
            $q->where($field[1], 'ILIKE', "%{$value}%");
        });
    }
}
