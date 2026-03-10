<?php

namespace App\Report\Http;

use App\CustomField\Enums\FieldParameter;
use App\Document\Http\DocumentCollection;
use App\Report\Report;

class ExportCollection extends DocumentCollection
{
    public $collects = ReportResource::class;

    protected string $modelName = Report::class;

    protected ?array $columnOrder = [
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
    ];

    protected function formatColumns()
    {
        return $this->modelName::resolveColumns($this->columnOrder, FieldParameter::ShowInExport);
    }
}
