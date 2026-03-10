<?php

namespace App\Report\Http;

use App\CustomField\Enums\FieldParameter;
use App\Document\Http\DocumentCollection;
use App\Report\Report;


class ReportCollection extends DocumentCollection
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
