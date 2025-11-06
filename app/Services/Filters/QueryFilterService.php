<?php

namespace App\Services\Filters;

use App\Services\Filters\Contracts\FilterStrategy;
use Illuminate\Database\Eloquent\Builder;


class QueryFilterService
{
    protected array $filterMap = [];

    public function registerFilter(string $key, FilterStrategy $filter): self
    {
        $this->filterMap[$key] = $filter;

        return $this;
    }

    public function apply(Builder $query, array $data): Builder
    {
        foreach ($data as $key => $value) {
            if (isset($this->filterMap[$key]) && $value !== null && $value !== '') {
                $this->filterMap[$key]->apply($query, $value);
            }
        }

        return $query;
    }
}
