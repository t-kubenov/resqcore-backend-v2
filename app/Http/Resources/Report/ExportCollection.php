<?php

namespace App\Http\Resources\Report;

use App\Enums\FieldParameter;
use App\Models\Report\Report;
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
        $attributes = array_merge(
            [
                'report_code',
                'call_date',
                'work_start_date',
                'close_date',
                'is_emergency_line',
                'line_facility_owner',
                'emergency_type',
                'emergency_location',
                'branch_worker_total',
                'worker_man_hours',
                'branch_office',
                'transport_totals',
                'is_closed',
            ],
            array_keys(array_filter(Report::fieldDefinitions(), fn($field) =>
            in_array(FieldParameter::ShowInExport, $field['parameters'])
            )
        ));

        return array_values(array_filter(array_map(
            function ($attributeName) {
                $field = collect(Report::fieldDefinitions())
                    ->firstWhere('name', $attributeName);

                return $field ? [
                    'key' => $field['name'],
                    'name' => $field['label'],
                    'alias' => $field['alias'],
                ] : null;
            },
            $attributes
        ))); // todo: get rid, add order to CustomFields
    }


    public function toArray(Request $request): array
    {
         return [
            'columns' => $this->getColumns(),
            'data' => $this->getCollection(),
         ];
    }

}
