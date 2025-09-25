<?php

namespace App\Http\Resources\Survey;

use App\Http\Resources\GenericResource;
use Illuminate\Http\Request;

class SurveyPrintResource extends GenericResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return array_merge(
            self::generatePrintColumns(), [
            'data' => [
                'id' => $this->id,
                'type' => 'survey_print',
                'attributes' => [
                    $this->getPrintData(),
                ],
                'relationships' =>
                    $this->violations->map(function($violation){
                        return [
                            'id' => $violation->id,
                            'type' => 'violation',
                            'attributes' => [
                                $violation->getPrintData(),
                            ]
                        ];
                    })->toArray(),
            ]]
        );
    }
}
