<?php

namespace App\Services\Filters\Configurations;

use App\Services\Filters\Filters\DateRangeFilter;
use App\Services\Filters\Filters\SearchFilter;
use App\Services\Filters\Filters\WhereFilter;
use App\Services\Filters\Filters\WhereInFilter;

class ReportFilterConfig extends BaseFilterConfig
{
    protected function filters(): array
    {
        return [
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
    }
}
