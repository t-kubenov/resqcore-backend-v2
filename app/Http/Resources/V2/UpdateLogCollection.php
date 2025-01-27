<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UpdateLogCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'columns' => $this->getColumns(),
            'data' => $this->resource,
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
            'created_at',
            'reported_by',
            'minor_injury_count',
            'severe_injury_count',
            'fatal_injury_count',
            'evacuated_people_count',
            'transport',
            'client_equipment_count',
            'branch_worker_count',
            'worker_man_hours',
            'third_parties_count',
            'work_scope',
            'situation_during_filing',
        ];

        return collect($attributes)->map(fn($attribute) => [
            'key' => $attribute,
            'name' => __('reports.fields.' . $attribute . '.name'),
            'alias' => __('reports.fields.' . $attribute . '.alias'),
        ])->toArray();
    }

}
