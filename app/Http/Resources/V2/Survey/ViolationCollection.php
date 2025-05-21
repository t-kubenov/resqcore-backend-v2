<?php

namespace App\Http\Resources\V2\Survey;

use App\Http\Resources\V2\GenericRelationshipResource;
use App\Models\V2\Survey\Survey;
use App\Models\V2\Survey\Violation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ViolationCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'columns' => $this->getColumns(),
            'data' => $this->transformCollection(),
        ];
    }

    protected function getColumns(): array
    {
        return Violation::columns();
    }

    protected function transformCollection(): \Illuminate\Support\Collection
    {
        return $this->collection->map(function ($survey) {
            return [
                'id' => $survey->id,
                'type' => 'survey',
                'attributes' => [
                    $survey->getReadableData(),
                ],
            ];
        });
    }
}
