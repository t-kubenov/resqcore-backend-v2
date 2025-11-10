<?php

namespace App\Services\Filters\Configurations;

use App\Services\Filters\Filters\SearchFilter;
use App\Services\Filters\Filters\WhereInFilter;
use App\Services\Filters\QueryFilterService;
use Illuminate\Database\Eloquent\Builder;

class VehicleFilterConfig
{
    public function __construct(
        protected QueryFilterService $filterService,
    ) {
        $this->configure();
    }

    protected function configure(): void
    {
        $filters = [
            'branch_office_id' => new WhereInFilter('branch_office_id'),
            'vehicle_type_id' => new WhereInFilter('vehicle_type_id'),
            'usage_type' => new WhereInFilter('usage_type'),
            'action' => new WhereInFilter('action'),
            'technical_condition' => new WhereInFilter('technical_condition'),
            'search' => new SearchFilter([
                'brand',
                'plate_number',
                'year',
                'location',
                'rental_contract',
                'notes',
            ])
        ];

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
