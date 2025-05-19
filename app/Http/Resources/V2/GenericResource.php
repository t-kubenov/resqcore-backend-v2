<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GenericResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return $this->formatResponse();
    }

    protected function formatResponse(): array
    {
        return [
            'data' => [
                'type' => strtolower(class_basename($this->resource)),
                'id' => $this->id,
                'attributes' => $this->attributesToArray(),
            ],
        ];
    }
}
