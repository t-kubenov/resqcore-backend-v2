<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

abstract class GenericResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return $this->formatResponse($this->cardColumns(), $this->getReadableData());
    }

    protected function formatResponse(array $columns, array $attributes, array $relationships = null): array
    {
        return [
            ...($columns? ['columns' => $columns]: []),
            'data' => [
                'type' => strtolower(class_basename($this->resource)),
                'id' => $this->id,
                'attributes' => $attributes,
                ...($relationships ? ['relationships' => $relationships] : []),
            ],
        ];
    }
}
