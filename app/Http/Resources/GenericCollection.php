<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Collection;

abstract class GenericCollection extends ResourceCollection
{
    protected string $modelName;
    protected ?array $columnOrder = null;


    public function toArray(Request $request): array
    {
        return $this->formatResponse($this->modelName::columns());
    }

    protected function formatResponse(array $columns): array
    {
        if ($this->columnOrder){
            $columns = $this->orderColumns($columns);
        }

        return [
            ...($columns? ['columns' => $columns]: []),
            'data' => $this->formatItems(),
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
