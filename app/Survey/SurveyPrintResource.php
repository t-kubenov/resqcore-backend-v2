<?php

namespace App\Survey;

use App\Document\Http\GenericResource;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SurveyPrintResource extends GenericResource
{
    public function toArray(Request $request): array
    {
        return array_merge(
            self::generatePrintColumns(), [
            'data' => [
                'id' => $this->id,
                'type' => 'survey_print',
                'attributes' => $this->getPrintData(),
                'relationships' =>
                    $this->violations->map(function($violation){
                        return [
                            'id' => $violation->id,
                            'type' => 'violation',
                            'attributes' => $violation->getPrintData(),
                        ];
                    })->toArray(),
            ]]
        );
    }

    public static function generatePrintColumns(): array
    {
        return collect(\Lang::get('surveys.print', [], 'ru'))
            ->map(fn($group) => collect($group)
                ->map(fn($value, $key) => ['key' => $key] + $value)
                ->values()
            )
            ->toArray();
    }

    public function getPrintData(): array
    {
        $data = $this->getReadableData();

        unset($data['survey_code']);

        return array_replace($data, [
            'branch_office_id' => $this->branch_office_id,
            'survey_date' => Carbon::parse($this->survey_date)->format('Y-m-d'),
        ]);
    }
}
