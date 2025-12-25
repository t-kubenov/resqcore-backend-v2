<?php

namespace App\Http\Controllers\Survey;

use App\Http\Requests\Survey\CloseViolationRequest;
use App\Http\Requests\Survey\StoreViolationRequest;
use App\Http\Resources\Survey\ViolationCollection;
use App\Http\Resources\Survey\ViolationResource;
use App\Http\Services\DocumentFormService;
use App\Http\Services\Survey\ViolationService;
use App\Models\Survey\Survey;
use App\Models\Survey\Violation;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;

class ViolationController
{
    use AuthorizesRequests;

    protected ViolationService $violationService;

    public function __construct(ViolationService $violationService)
    {
        $this->violationService = $violationService;
    }

    public function create(DocumentFormService $service)
    {
        $fields = $service->getCategorizedCreateFields(Violation::class);

        return response()->json([
            'categories' => $fields
        ]);
    }

    public function store(StoreViolationRequest $request, int $id): ViolationResource
    {
        $survey = Survey::findOrFail($id);

        $this->authorize('create', Survey::class);

        return new ViolationResource($this->violationService->store($request, $survey));
    }

    public function close(CloseViolationRequest $request, int $id): ViolationResource|JsonResponse
    {
        $violation = Violation::findOrFail($id);

        $this->authorize('create', Survey::class);

        if ($violation->elimination_completion) {
            return response()->json(['message' => __('validation.survey.violations.close.already_closed')], 422);
        }

        return new ViolationResource($this->violationService->close($request, $violation));
    }

    public function index(int $surveyId): ViolationCollection
    {
        $survey = Survey::findOrFail($surveyId);

        $this->authorize('view', $survey);

        $violations = Violation::query()
            ->where('survey_id', $surveyId)
            ->orderBy('created_at', 'desc')
            ->paginate();


        return new ViolationCollection($violations);
    }

    public function show(int $id): ViolationResource
    {
        $violation = Violation::findOrFail($id);

        $this->authorize('view', $violation->survey);

        return new ViolationResource($violation);
    }
}
