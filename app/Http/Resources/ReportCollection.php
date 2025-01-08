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
            'data' => $this->collection->transform(function ($report) {
                return [
                    'id' => $report->id,
                    'type' => 'report',
                    'attributes' => [
                        'report_code' => $report->report_code,
                        'branch_office' => $report->branch_office,
                        'caller' => $report->caller,
                        'call_date' => $report->call_date->format(config('app.date_format')),
                        'is_emergency_line' => $report->is_emergency_line,
                        'facility_name' => $report->facility->location ?? $report->line_facility_location,
                        'facility_owner' => $report->facility_owner,
                        'line_facility_owner' => $report->line_facility_owner,
                        'emergency_type' => $report->emergency, // Derive from emergency type
                        'emergency_location' => $report->emergency_location ?? 'Н/Д',
                        'branch_worker_count' => $report->branch_worker_count,
                        'worker_man_hours' => $report->worker_man_hours,
                        'is_closed' => $report->is_closed,
                        'close_date' => $report->close_date,
                        'transport' => $report->transport,
                    ],
                ];
            }),
            'meta' => [
                'total_reports' => $this->collection->count(),
                // Add other meta information as needed
            ],
        ];
    }
}
