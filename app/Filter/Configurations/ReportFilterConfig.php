<?php

namespace App\Filter\Configurations;

use App\Filter\Filters\DateRangeFilter;
use App\Filter\Filters\SearchFilter;
use App\Filter\Filters\WhereFilter;
use App\Filter\Filters\WhereInFilter;

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
