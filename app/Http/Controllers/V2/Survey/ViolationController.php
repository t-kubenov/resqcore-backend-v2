<?php

namespace App\Http\Controllers\V2\Survey;

use App\Enums\AccessLevel;
use App\Http\Requests\V2\Survey\CloseViolationRequest;
use App\Http\Requests\V2\Survey\StoreViolationRequest;
use App\Http\Resources\V2\Survey\ViolationCollection;
use App\Http\Resources\V2\Survey\ViolationResource;
use App\Http\Services\V2\Survey\ViolationService;
use App\Models\Report;
use App\Models\V2\Survey\Survey;
use App\Models\V2\Survey\Violation;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ViolationController
{
    use AuthorizesRequests;

    protected ViolationService $violationService;

    public function __construct(ViolationService $violationService)
    {
        $this->violationService = $violationService;
    }

    /**
     * @throws AuthorizationException
     */
    public function store(StoreViolationRequest $request, int $id): ViolationResource
    {
        $survey = Survey::findOrFail($id);

        $this->authorize('create', Report::class); // todo: replace with survey, maybe move to middleware

        $this->violationService->authorizeBranchAccess($survey);

        return new ViolationResource($this->violationService->store($request, $survey));
    }

    public function close(CloseViolationRequest $request, int $id): ViolationResource|JsonResponse
    {
        $violation = Violation::findOrFail($id);

        $this->authorize('create', Report::class); // todo: replace with survey, maybe move to middleware

        $this->violationService->authorizeBranchAccess($violation->survey);

        if ($violation->elimination_completion) {
            return response()->json(['message' => __('validation.survey.violations.close.already_closed')], 422);
        }

        return new ViolationResource($this->violationService->close($request, $violation));
    }

    public function index(int $surveyId): ViolationCollection
    {
        $survey = Survey::findOrFail($surveyId);

        $this->violationService->authorizeBranchAccess($survey);

        $violations = Violation::query()
            ->where('survey_id', $surveyId)
            ->orderBy('created_at', 'desc')
            ->paginate(15);


        return new ViolationCollection($violations);
    }

    public function show(int $id): ViolationResource
    {
        $violation = Violation::findOrFail($id);

        $this->violationService->authorizeBranchAccess($violation->survey);

        return new ViolationResource($violation);
    }
}
