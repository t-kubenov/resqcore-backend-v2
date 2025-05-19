<?php

namespace App\Http\Resources\V2\Survey;

use App\Http\Resources\V2\GenericRelationshipResource;
use App\Models\V2\Survey\Survey;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SurveyCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'columns' => $this->getColumns(),
            'data' => $this->transformCollection(),
        ];
    }

    /**
     * Generate column definitions for the table.
     *
     * @return array
     */
    protected function getColumns(): array
    {
        return
            Survey::columns();


    }

    /**
     * Transform the collection into the desired data structure.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function transformCollection(): \Illuminate\Support\Collection
    {
        return $this->collection->map(function ($survey) {
            return [
                'id' => $survey->id,
                'type' => 'survey',
                'attributes' => [
                    $survey->resource
                ],
                'relationships' => $this->getRelationships($survey),
            ];
        });
    }

    /**
     * Generate relationships for the report.
     *
     * @param mixed $survey
     * @return array
     */
    protected function getRelationships($survey): array
    {
        return [
            'branch_office' => new GenericRelationshipResource($survey->branch_office),
        ];
    }

    protected function getCustomFieldColumns()
    {

    }
}
