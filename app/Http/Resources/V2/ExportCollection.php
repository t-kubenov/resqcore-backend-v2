<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ExportCollection extends ReportCollection
{
    /**
     * Generate column definitions for the table.
     *
     * @return array
     */
    protected function getColumns(): array
    {
        $attributes = [
            'start' => [
                'report_code',
            ],
            'middle' => [
                'work_start_date',
                'close_date',
                'facility_owner',
                'emergency_type',
                'emergency_location',
                'branch_worker_count',
                'worker_man_hours',
            ],
            'end' => [
                'branch_worker_count',
                'worker_man_hours',
                'transport_totals'
            ],
        ];

        return collect($attributes)->mapWithKeys(fn($group, $key) => [
            $key => collect($group)->map(fn($attribute) => [
                'key' => $attribute,
                'name' => __('reports.fields.' . $attribute . '.name'),
                'alias' => __('reports.fields.' . $attribute . '.alias'),
            ])->toArray(),
        ])->toArray();
    }

}
