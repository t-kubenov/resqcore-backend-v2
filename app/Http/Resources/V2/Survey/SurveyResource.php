<?php

namespace App\Http\Resources\V2\Survey;

use App\Http\Resources\V2\GenericResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SurveyResource extends GenericResource
{
    protected function formatResponse(): array
    {
        return [
            'columns' => $this->cardColumns(),
            'data' => [
                'type' => strtolower(class_basename($this->resource)),
                'id' => $this->id,
                'attributes' => $this->getReadableData(),
            ],
        ];
    }
}
