<?php

namespace App\Survey\Violation\Http;

use App\Document\DocumentFormService;
use App\Survey\Models\Survey;
use App\Survey\Violation\Violation;
use App\Survey\Violation\ViolationService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

    public function close_v2(CloseViolationRequest $request, int $id): ViolationResource|JsonResponse
    {
        $violation = Violation::findOrFail($id);

        $this->authorize('create', Survey::class);

        if ($violation->elimination_completion) {
            return response()->json(['message' => __('validation.survey.violations.close.already_closed')], 422);
        }

        return new ViolationResource($this->violationService->close($request, $violation));
    }

    public function close(CloseViolationRequest $request, Survey $survey, Violation $violation): ViolationResource|JsonResponse
    {
        if (!$violation->belongsToSurvey($survey)){
            throw new NotFoundHttpException(__('reports.show.not-found'));
        }

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

    public function show_v2(int $id): ViolationResource
    {
        $violation = Violation::findOrFail($id);

        $this->authorize('view', $violation->survey);

        return new ViolationResource($violation);
    }

    public function show(Survey $survey, Violation $violation): ViolationResource
    {
        if (!$violation->belongsToSurvey($survey)){
            throw new NotFoundHttpException(__('reports.show.not-found'));
        }

        $this->authorize('view', $survey);

        return new ViolationResource($violation);
    }
}
