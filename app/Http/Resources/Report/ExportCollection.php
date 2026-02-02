<?php

namespace App\Http\Resources\Report;

use App\Http\Resources\GenericCollection;
use App\Models\Report\Report;

class ExportCollection extends GenericCollection
{
    protected string $modelName = Report::class; //todo: order columns

    protected array $tableColumnOrder = [
        'report_code',
        'call_date',
        'work_start_date',
        'close_date',
        'is_emergency_line',
        'line_facility_owner',
        'emergency_type',
        'emergency_location',
        'branch_worker_total',
        'worker_man_hours',
        'branch_office',
        'is_closed',
        'emergency_resolved_by',
        // custom fields here
    ];
}
