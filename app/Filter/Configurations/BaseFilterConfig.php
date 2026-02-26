<?php

namespace App\Filter\Configurations;

use App\Filter\QueryFilterService;
use Illuminate\Database\Eloquent\Builder;

abstract class BaseFilterConfig
{
    abstract protected function filters(): array;

    public function __construct(protected QueryFilterService $filterService)
    {
        $this->configure();
    }

    protected function configure(): void
    {
        $filters = $this->filters();

        foreach ($filters as $field => $filter) {
            $this->filterService->registerFilter($field, $filter);
        }
    }

    public function apply(Builder $query, array $data): Builder
    {
        $this->filterService->apply($query, $data);

        return $query;
    }
}
