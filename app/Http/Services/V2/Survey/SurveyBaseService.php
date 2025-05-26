<?php

namespace App\Http\Services\V2\Survey;

use App\Models\V2\Survey\Survey;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use App\Enums\AccessLevel;


abstract class SurveyBaseService
{
    public function throttle(Request $request): void // maybe: move to middleware
    {
        if (config('app.debug')) {
            return;
        }

        $key = 'survey-create:' . (auth()->user()?->id ?: $request->ip());

        if (RateLimiter::tooManyAttempts($key, 1)) {
            throw new ThrottleRequestsException(__('throttle.report-create'));
        }

        RateLimiter::hit($key, 60);
    }

    public function authorizeBranchAccess(Survey $survey): void
    {
        if (auth()->user()->access_level == AccessLevel::APP) {
            return;
        }

        if ($survey->branch_office_id !== auth()->user()->branch_id) {
            abort(403, __('validation.survey.violations.create.unauthorized'));
        }
    }
}
