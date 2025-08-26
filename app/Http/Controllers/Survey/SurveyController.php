<?php

namespace App\Http\Controllers\Survey;

use App\Http\Requests\V2\Survey\FilterSurveyRequest;
use App\Http\Requests\V2\Survey\StoreSurveyRequest;
use App\Http\Resources\Survey\PrintResource;
use App\Http\Resources\Survey\SurveyCollection;
use App\Http\Resources\Survey\SurveyResource;
use App\Http\Services\Survey\SurveyService;
use App\Models\V2\Survey\Survey;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\JsonResponse;

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

    /**
     * @throws AuthorizationException
     */
    public function print(int $id)
    {
        $survey = Survey::findOrFail($id);

        $this->authorize('view', $survey);

        return new PrintResource($survey);
    }

    /**
     * @throws AuthorizationException
     */
    public function show(int $id)
    {
        $survey = Survey::findOrFail($id);

        $this->authorize('view', $survey);

        return new SurveyResource($survey);
    }

    public function store(StoreSurveyRequest $request): SurveyResource|JsonResponse
    {
        $this->authorize('create', Survey::class);

        try {
            $survey = $this->surveyService->store($request);
        } catch (ThrottleRequestsException $e) {
            return response()->json(['message' => __('throttle.report-create')], 429);
        }

        return new SurveyResource($survey);
    }
}
