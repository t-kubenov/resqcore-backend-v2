<?php

namespace App\Document\Http;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Collection;

abstract class DocumentCollection extends ResourceCollection
{
    protected string $modelName;


    public function toArray(Request $request): array
    {
        return [
            'columns' => $this->formatColumns(),
            'data' => $this->formatData(),
        ];
    }

    protected function formatColumns()
    {
        return $this->modelName::tableColumns();
    }

    protected function formatData(): Collection // todo: this should be managed in the Resource
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
