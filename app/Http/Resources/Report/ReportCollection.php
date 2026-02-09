<?php

namespace App\Http\Resources\Report;

use App\Enums\FieldParameter;
use App\Http\Resources\GenericCollection;
use App\Models\Report\Report;


class ReportCollection extends GenericCollection
{
    protected string $modelName = Report::class;

    protected array $columnOrder = [
        'report_code',
        'facility_owner',
        'emergency_type',
        'emergency_location',
        'branch_office',
        'is_closed',
    ];

    protected function formatColumns()
    {
        return $this->modelName::resolveColumns($this->columnOrder, FieldParameter::ShowInTable);
    }
}
