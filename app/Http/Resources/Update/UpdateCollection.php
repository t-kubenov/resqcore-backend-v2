<?php

namespace App\Http\Resources\Update;

use App\Models\Report\Report;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UpdateCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'columns' => $this->getColumns(array_keys($this->resource[0]->customFieldValueArray())),
            'data' => $this->resource,
        ];
    }

    protected function getColumns(array $customFields = []): array
    {
        $attributes = array_merge([
            'created_at',
            'minor_injury_count',
            'severe_injury_count',
            'fatal_injury_count',
            'evacuated_people_count',
            'client_equipment_count',
            'third_parties_count',
            'branch_worker_count',
            'worker_man_hours',
            'work_scope',
            'situation_during_filing',
            'transport',
        ], $customFields);

        return collect(array_intersect_key(Report::fieldDefinitions(), array_flip($attributes)))->map(fn($attribute, $key) => [ //repeating piece of code x3
            'key' => $key,
            'name' => $attribute['label'],
            'alias' => $attribute['alias'],
        ])->values()->toArray();
    }

}
