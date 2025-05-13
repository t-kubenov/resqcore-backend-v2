<?php

namespace App\Http\Controllers\V2\Survey;

use App\Http\Requests\V2\Survey\StoreSurveyRequest;
use App\Http\Services\V2\Survey\SurveyService;
use App\Models\Report;
use App\Models\V2\Survey\Survey;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SurveyController
{
    use AuthorizesRequests;

    protected SurveyService $surveyService;

    public function __construct(SurveyService $surveyService)
    {
        $this->surveyService = $surveyService;
    }

    public function index(Request $request)
    {
        return ":3";
    }

    public function show($id)
    {
        // Your logic to handle the request and return a response
        return response()->json(['message' => 'Survey show', 'id' => $id]);
    }

    public function store(StoreSurveyRequest $request)
    {
        $this->authorize('create', Report::class); // todo: replace with survey, maybe move to middleware

        try {
            $survey = $this->surveyService->handleCreate($request);
        } catch (ThrottleRequestsException $e) {
            return response()->json(['message' => __('throttle.report-create')], 429);
        }

        return $survey;
    }

    public function update(Request $request, $id)
    {
        // Your logic to handle the request and return a response
        return response()->json(['message' => 'Survey update', 'id' => $id]);
    }

    public function destroy($id)
    {
        // Your logic to handle the request and return a response
        return response()->json(['message' => 'Survey destroy', 'id' => $id]);
    }
}
