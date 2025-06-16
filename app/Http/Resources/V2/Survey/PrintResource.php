<?php

namespace App\Http\Resources\V2\Survey;

use App\Http\Resources\V2\GenericResource;
use App\Models\V2\Survey\Violation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PrintResource extends GenericResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $violation = new Violation();

        return [
            'columns' =>
                self::generatePrintColumns(),
            'table_columns' =>
                Violation::generatePrintColumns(),

            'data' => [
                'id' => $this->id,
                'type' => 'survey_print',
                'attributes' => [
                    $this->getReadableData(),
                ],
                'relationships' =>
                    $this->violations->map(function($violation_){
                        return [
                            'id' => $violation_->id,
                            'type' => 'violation',
                            'attributes' => [
                                $violation_->toArray(),
                            ]
                        ];
                    })->toArray(),
            ]
        ];
    }
}
