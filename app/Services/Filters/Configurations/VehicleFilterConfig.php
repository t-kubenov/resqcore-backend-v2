<?php

namespace App\Services\Filters\Configurations;

use App\Services\Filters\Filters\SearchFilter;
use App\Services\Filters\Filters\WhereInFilter;

class VehicleFilterConfig extends BaseFilterConfig
{
    protected function filters(): array
    {
        return [
            'branch_office_id' => new WhereInFilter('branch_office_id'),
            'vehicle_type_id' => new WhereInFilter('vehicle_type_id'),
            'usage_type' => new WhereInFilter('usage_type'),
            'action' => new WhereInFilter('action'),
            'technical_condition' => new WhereInFilter('technical_condition'),
            'search' => new SearchFilter([
                'id',
                'brand',
                'plate_number',
                'year',
                'location',
                'rental_contract',
                'notes',
            ])
        ];
    }
}
