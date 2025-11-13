<?php

namespace App\Observers\Survey;

use App\Models\Survey\Survey;
use Carbon\Carbon;

class SurveyObserver
{
    public function creating(Survey $survey): void
    {
        $user = auth()->user();

        if (!$survey->branch_office_id) {
            $survey->branch_office_id = $user?->branch_id;
        }

        if (!$survey->created_by) {
            $survey->created_by = $user?->id;
        }

        if (!$survey->survey_code) {
            $survey->survey_code = $this->generateSurveyCode($survey);
        }
    }

    private function generateSurveyCode(Survey $survey): string
    {
        $year = Carbon::now()->format('y');
        $branchId = $survey->branch_office_id;

        $lastSurvey = Survey::where('branch_office_id', $branchId)
            ->whereYear('created_at', Carbon::now()->year)
            ->latest('id')
            ->first();

        $nextCount = $lastSurvey? explode('-', $lastSurvey->survey_code)[2]: 0;

        return $branchId . '-' . $year . '-'. ++$nextCount;
    }

}
