<?php

namespace App\Report\V2;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportResource extends JsonResource
{
    protected $transport;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
    {
        return [
            'columns' => $this->getColumns(),
            'data' =>
            [
                'id' => $this->id,
                'type' => 'report',
                'attributes' => $this->getReadableData(),
            ],
        ];
    }

    public function getColumns(){
        $highlighted = [
            'report_code',
            'is_closed',
        ];
        $attributes = array_diff(
            array_keys($this->getReadableData()),
            $highlighted
        );

        return [
            'highlighted' => collect(array_intersect_key(self::fieldDefinitions(), array_flip($highlighted)))->map(fn($attribute, $key) => [
                'key' => $key,
                'name' => $attribute['label'],
                'alias' => $attribute['alias'],
            ])->values()->toArray(),
            'attributes' => collect(array_intersect_key(self::fieldDefinitions(), array_flip($attributes)))->map(fn($attribute, $key) => [
                'key' => $key,
                'name' => $attribute['label'],
                'alias' => $attribute['alias'],
            ])->values()->toArray(),
        ];
    }

}
