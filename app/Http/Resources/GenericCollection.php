<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Collection;

abstract class GenericCollection extends ResourceCollection
{
    protected string $modelName;

    public function toArray(Request $request): array
    {
        return $this->formatResponse($this->modelName::columns());
    }

    protected function formatResponse(array $columns): array
    {
        return [
            ...($columns? ['columns' => $columns]: []),
            'data' => $this->formatItems(),
        ];
    }

    protected function formatItems(): Collection
    {
        return $this->collection->map(function ($item) {
            return [
                'id' => $item->id,
                'type' => strtolower(class_basename($this->modelName)),
                'attributes' => $item->getReadableData(),
            ];
        });
    }
}
