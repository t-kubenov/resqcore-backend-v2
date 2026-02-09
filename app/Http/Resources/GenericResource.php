<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

abstract class GenericResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'columns' => $this->formatColumns(),
            'data' => $this->formatData(),
        ];
    }

    protected function formatColumns()
    {
        return $this::cardColumns();
    }

    protected function formatData(): array
    {
        return [
            'type' => strtolower(class_basename($this->resource)),
            'id' => $this->id,
            'attributes' => $this->getReadableData(),
        ];
    }
}
