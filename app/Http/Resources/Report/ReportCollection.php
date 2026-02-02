<?php

namespace App\Http\Resources\Report;

use App\Http\Resources\GenericCollection;
use App\Models\Report\Report;


class ReportCollection extends GenericCollection
{
    protected array $tableColumnOrder = [
        'report_code',
        'facility_owner',
        'emergency_type',
        'emergency_location',
        'branch_office',
        'is_closed',
        // custom fields here
    ];

    protected string $modelName = Report::class; //todo: order columns
}
