<?php

namespace App\Http\Resources\Report;

use App\Http\Resources\GenericRelationshipResource;
use App\Http\Resources\TransportCollection;
use App\Models\V2\Report\Report;
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
        return [
            "start" => [
                [
                    "key" => "report_code",
                    "name" => "Номер отчета",
                    "alias" => ""
                ]
            ],
            "middle" => [
                [
                    "key" => "facility_owner",
                    "name" => "Наименование объекта",
                    "alias" => ""
                ],
                [
                    "key" => "emergency_type",
                    "name" => "Тип ЧС",
                    "alias" => ""
                ],
                [
                    "key" => "emergency_location",
                    "name" => "Место ЧС",
                    "alias" => ""
                ],
            ],
            "end" => array_merge([
                [
                    "key" => "branch_office",
                    "name" => "Филиал",
                    "alias" => ""
                ],
                [
                    "key" => "is_closed",
                    "name" => "Ликвидирован",
                    "alias" => "Статус"
                ]
            ], Report::exportColumns()),
        ];
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
                'attributes' => array_merge([
                    'report_code' => $report->report_code,
                    'branch_office' => $report->branch_office?->name,
                    'caller' => $report->caller,
                    'call_date' => $report->call_date?->format(config('app.date_format')),
                    'is_emergency_line' => $report->is_emergency_line,
                    'facility_name' => $report->facility->location ?? $report->line_facility_location,
                    'facility_owner' => $report->facility_owner?->name ?? $report->line_facility_owner,
                    'emergency_type' => $report->emergency?->name,
                    'emergency_location' => $report->emergency_location,
                    'worker_man_hours' => $report->worker_man_hours,
                    'is_closed' => $report->is_closed,
                    'close_date' => $report->close_date?->format(config('app.date_format')),
                ],
                $report->customFieldValueArray()
                ),
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
            'branch_office' => new GenericRelationshipResource($report->branch_office),
            'facility_owner' => new GenericRelationshipResource($report->facility_owner),
            'emergency_type' => new GenericRelationshipResource($report->emergency),
            'transport' => new TransportCollection($report->transport??[]),
            'transport_totals' => $report->transport_totals,
        ];
    }
}
