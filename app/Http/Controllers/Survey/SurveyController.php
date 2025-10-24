<?php

namespace App\Http\Controllers\Survey;

use App\Http\Requests\Survey\FilterSurveyRequest;
use App\Http\Requests\Survey\StoreSurveyRequest;
use App\Http\Resources\Survey\PrintResource;
use App\Http\Resources\Survey\SurveyCollection;
use App\Http\Resources\Survey\SurveyResource;
use App\Http\Services\DocumentFormService;
use App\Http\Services\Survey\SurveyService;
use App\Models\Survey\Survey;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\JsonResponse;

class SurveyController
{
    use AuthorizesRequests;

    public function __construct(protected SurveyService $surveyService){}

    public function index(FilterSurveyRequest $request)
    {
        $collection = $this->surveyService->index($request);

        return new SurveyCollection($collection);
    }

    public function show(int $id)
    {
        $survey = Survey::findOrFail($id);

        $this->authorize('view', $survey);

        return new SurveyResource($survey);
    }

    public function create(DocumentFormService $service)
    {
        $fields = $service->getCategorizedCreateFields(Survey::class);

        return response()->json([
            'fields' => $fields
        ]);
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

    public function export(FilterSurveyRequest $request)
    {
        $collection = $this->surveyService->index($request);

        return new SurveyCollection($collection);
    }

    public function print(int $id)
    {
        $survey = Survey::findOrFail($id);

        $this->authorize('view', $survey);

        return new PrintResource($survey);
    }
}
