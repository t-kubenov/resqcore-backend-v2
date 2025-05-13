<?php

namespace App\Http\Services\V2\Survey;

use App\Http\Requests\V2\Survey\StoreSurveyRequest;
use App\Models\V2\Survey\Survey;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

class SurveyService
{
    public function handleCreate(StoreSurveyRequest $request): Survey
    {
        $this->throttle($request);
        return $this->create($request);
    }


    private function throttle(Request $request): void
    {
        if (config('app.debug')) {
            return;
        }

        $key = 'survey-create:' . (Auth::user()?->id ?: $request->ip());

        if (RateLimiter::tooManyAttempts($key, 1)) {
            throw new ThrottleRequestsException(__('throttle.report-create'));
        }

        RateLimiter::hit($key, 60);
    }

    private function create(StoreSurveyRequest $request): Survey
    {
        $data = $request->validated()['data']['attributes'];

        $survey = new Survey();
        $survey->fill($data);
        $survey->save();

        return $survey;
    }

}
