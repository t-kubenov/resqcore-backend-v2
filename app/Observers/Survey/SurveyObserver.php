<?php

namespace App\Observers\Survey;

use App\Models\User;
use App\Models\Survey\Survey;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

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
        $year = Carbon::create($survey->survey_date)->format('y');
        $branchId = $survey->branch_office_id;
        $surveyCount = Survey::where('branch_office_id', $branchId)->count() + 1;

        return "{$branchId}-{$year}-{$surveyCount}"; //todo: need to make it so that the surveyCount resets on the new year
    }
}
