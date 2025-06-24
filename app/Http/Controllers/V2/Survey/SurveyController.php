<?php

namespace App\Http\Controllers\V2\Survey;

use App\Http\Requests\V2\Survey\FilterSurveyRequest;
use App\Http\Requests\V2\Survey\StoreSurveyRequest;
use App\Http\Resources\V2\Survey\PrintResource;
use App\Http\Resources\V2\Survey\SurveyCollection;
use App\Http\Resources\V2\Survey\SurveyResource;
use App\Http\Services\V2\Survey\SurveyService;
use App\Models\Report;
use App\Models\V2\Survey\Survey;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SurveyController
{
    use AuthorizesRequests;

    protected SurveyService $surveyService;

    public function __construct(SurveyService $surveyService)
    {
        $this->surveyService = $surveyService;
    }

    public function index(FilterSurveyRequest $request)
    {
        return new SurveyCollection($this->surveyService->index($request));
    }

    public function export(FilterSurveyRequest $request)
    {
        return new SurveyCollection($this->surveyService->index($request));
    }

    public function print(int $id)
    {
        $survey = Survey::findOrFail($id);

        return new PrintResource($survey);
    }

    public function show(int $id)
    {
        $survey = Survey::findOrFail($id);

        return new SurveyResource($survey);
    }

    public function store(StoreSurveyRequest $request): SurveyResource|JsonResponse
    {
        $this->authorize('create', Report::class); // todo: replace with survey, maybe move to middleware

        try {
            $survey = $this->surveyService->store($request);
        } catch (ThrottleRequestsException $e) {
            return response()->json(['message' => __('throttle.report-create')], 429);
        }

        return new SurveyResource($survey);
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
