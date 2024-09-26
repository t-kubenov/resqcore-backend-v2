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
        // return parent::toArray($request);

        return [
            'data' => $this->collection->transform(function ($report) {
                return [
                    'id' => $report->id,
                    'type' => 'report',
                    'attributes' => [
                        'report_code' => $report->report_code,
                        'caller' => $report->caller,
                        'facility_name' => $report->facility->location ?? $report->line_facility_location,
                        'emergency_location' => $report->emergency_location ?? 'Н/Д',
                        'call_date' => $report->call_date,
                        'emergency_name' => $report->emergency, // Derive from emergency type
                        'is_closed' => $report->is_closed,
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
