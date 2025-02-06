<?php

namespace App\Http\Resources\V2\Report;

use Illuminate\Http\Request;

class ExportCollection extends ReportCollection
{
    /**
     * The resource that this resource collects.
     *
     * @var string
     */
    public $collects = ExportResource::class;


    /**
     * Generate column definitions for the table.
     *
     * @return array
     */
    protected function getColumns(): array
    {
        $attributes = [
            'report_code',
            'call_date',
            'work_start_date',
            'close_date',
            'is_emergency_line',
            'line_facility_owner',
            'emergency_type',
            'emergency_location',
            'branch_worker_count',
            'worker_man_hours',
            'branch_office',
            'transport_totals',
            'is_closed',
        ];

        return
            collect($attributes)->map(fn($attribute) => [
                'key' => $attribute,
                'name' => __('reports.fields.' . $attribute . '.name'),
                'alias' => __('reports.fields.' . $attribute . '.alias'),
            ])->toArray();
    }

    public function toArray(Request $request): array
    {
        return [
            'columns' => $this->getColumns(),
            'data' => $this->getCollection(),
        ];
    }

}
