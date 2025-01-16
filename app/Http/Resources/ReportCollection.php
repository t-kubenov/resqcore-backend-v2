<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ReportCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'columns' => $this->getColumns(),
            'data' => $this->transformCollection(),
        ];
    }

    /**
     * Generate column definitions for the table.
     *
     * @return array
     */
    protected function getColumns(): array
    {
        $attributes = [
            'report_code',
            'branch_office',
            'caller',
            'call_date',
            'is_emergency_line',
            'facility_name',
            'facility_owner',
            'line_facility_owner',
            'emergency_type',
            'emergency_location',
            'branch_worker_count',
            'worker_man_hours',
            'is_closed',
            'close_date',
            'transport',
        ];

        return collect($attributes)->map(fn($attribute) => [
            'key' => $attribute,
            'name' => __('reports.fields.' . $attribute . '.name'),
            'alias' => __('reports.fields.' . $attribute . '.alias'),
        ])->toArray();
    }

    /**
     * Transform the collection into the desired data structure.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function transformCollection(): \Illuminate\Support\Collection
    {
        return $this->collection->map(function ($report) {
            return [
                'id' => $report->id,
                'type' => 'report',
                'attributes' => [
                    'report_code' => $report->report_code,
                    'branch_office' => $report->branch_office?->name,
                    'caller' => $report->caller,
                    'call_date' => $report->call_date?->format(config('app.date_format')),
                    'is_emergency_line' => $report->is_emergency_line,
                    'facility_name' => $report->facility->location ?? $report->line_facility_location,
                    'facility_owner' => $report->facility_owner?->name,
                    'line_facility_owner' => $report->line_facility_owner,
                    'emergency_type' => $report->emergency?->name,
                    'emergency_location' => $report->emergency_location,
                    'branch_worker_count' => $report->branch_worker_count,
                    'worker_man_hours' => $report->worker_man_hours,
                    'is_closed' => $report->is_closed,
                    'close_date' => $report->close_date?->format(config('app.date_format')),
                ],
                'relationships' => $this->getRelationships($report),
            ];
        });
    }

    /**
     * Generate relationships for the report.
     *
     * @param mixed $report
     * @return array
     */
    protected function getRelationships($report): array
    {
        return [
            'branch_office' => $report->branch_office,
            'facility_owner' => $report->facility_owner,
            'emergency' => $report->emergency,
            'transport' => $report->transport,
        ];
    }
}
