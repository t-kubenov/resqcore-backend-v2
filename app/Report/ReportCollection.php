<?php

namespace App\Report;

use App\Document\Http\GenericCollection;
use App\CustomField\Enums\FieldParameter;


class ReportCollection extends GenericCollection
{
    public $collects = ReportResource::class;

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
