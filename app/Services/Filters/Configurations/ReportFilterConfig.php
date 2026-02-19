<?php

namespace App\Services\Filters\Configurations;

use App\Services\Filters\Filters\DateRangeFilter;
use App\Services\Filters\Filters\SearchFilter;
use App\Services\Filters\Filters\WhereFilter;
use App\Services\Filters\Filters\WhereInFilter;
use App\Services\Filters\QueryFilterService;
use Illuminate\Database\Eloquent\Builder;

class ReportFilterConfig
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
            'emergency_type' => new WhereInFilter('emergency_type'),
            'is_emergency_line' => new WhereFilter('is_emergency_line'),
            'call_date' => new DateRangeFilter('call_date'),
            'is_closed' => new WhereFilter('is_closed'),
            'search' => new SearchFilter([
                'report_code',
                'line_facility_owner',
                'emergency_location'
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
