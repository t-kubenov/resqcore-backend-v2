<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

abstract class GenericResource extends JsonResource
{
    protected ?array $columnOrder = null;

    public function toArray(Request $request): array
    {
        return $this->formatResponse($this->cardColumns(), $this->getReadableData());
    }

    protected function formatResponse(array $columns, array $attributes, array $relationships = null): array
    {
        if ($this->columnOrder){
            $columns = $this->orderColumns($columns);
        }

        return [
            ...($columns? ['columns' => $columns]: []),
            'data' => $this->formatData($attributes, $relationships),
        ];
    }

    protected function orderColumns(array $columns): array
    {
        $order = array_flip($this->columnOrder);

        usort($columns, function ($a, $b) use ($order) {
            return ($order[$a['key']] ?? PHP_INT_MAX)
                <=> ($order[$b['key']] ?? PHP_INT_MAX);
        });

        return $columns;
    }

    protected function formatData(array $attributes, array $relationships = null): array
    {
        return [
            'type' => strtolower(class_basename($this->resource)),
            'id' => $this->id,
            'attributes' => $attributes,
            ...($relationships ? ['relationships' => $relationships] : []),
        ];
    }
}
