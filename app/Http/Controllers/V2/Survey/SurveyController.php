<?php

namespace App\Http\Controllers\V2\Survey;

use App\Http\Requests\V2\Survey\StoreSurveyRequest;
use App\Http\Requests\V2\Survey\StoreViolationRequest;
use App\Http\Resources\V2\Survey\SurveyCollection;
use App\Http\Resources\V2\Survey\SurveyResource;
use App\Http\Resources\V2\Survey\ViolationResource;
use App\Http\Services\V2\Survey\SurveyService;
use App\Models\Report;
use App\Models\V2\Survey\Survey;
use App\Models\V2\Survey\Violation;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\JsonResponse;
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
        return new SurveyCollection($this->surveyService->index($request));
    }

    public function show($id)
    {
        return response()->json(['message' => 'Survey show', 'id' => $id]);
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

    public function storeViolation(StoreViolationRequest $request, int $id): ViolationResource
    {
        $survey = Survey::findOrFail($id);

        $this->authorize('create', Report::class); // todo: replace with survey, maybe move to middleware

        $this->authorizeBranchAccess($survey);

        return new ViolationResource($this->surveyService->storeViolation($request, $survey));
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

    private function authorizeBranchAccess(Survey $survey): void
    {
        if ($survey->branch_office_id !== auth()->user()->branch_id) {
            abort(403, __('validation.survey.violations.create.unauthorized'));
        }
    }
}
