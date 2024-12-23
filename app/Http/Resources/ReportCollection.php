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
                        'branch_office_id' => $report->branch_office_id,
                        'caller' => $report->caller,
                        'facility_name' => $report->facility->location ?? $report->line_facility_location,
                        'emergency_location' => $report->emergency_location ?? 'Н/Д',
                        'call_date' => $report->call_date->format(config('app.date_format')),
                        'emergency_name' => $report->emergency, // Derive from emergency type
                        'is_closed' => $report->is_closed,
                        'is_emergency_line' => $report->is_emergency_line,
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
