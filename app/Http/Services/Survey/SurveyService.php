<?php

namespace App\Http\Services\Survey;

use App\Http\Requests\Survey\StoreSurveyRequest;
use App\Http\Services\GeneralService;
use App\Models\Survey\Survey;
use App\Models\Survey\SurveyLegalEntityUnit;
use App\Services\Filters\Configurations\SurveyFilterConfig;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class SurveyService extends GeneralService
{
    public function __construct(protected SurveyFilterConfig $filterConfig)
    {
    }

    public function index(Request $request)
    {
        $query = Survey::query()
            ->orderBy('created_at', 'desc');

        $this->filterQuery($query, $request);

        return $query->paginate();
    }

    private function filterQuery(Builder $query, $request): Builder
    {
        $this->filterByRoles($query);

        $this->filterConfig->apply($query, $request->all());

        return $query;
    }

    public function store(StoreSurveyRequest $request): Survey
    {
        $this->throttle($request);
        return $this->create($request);
    }

    private function create(StoreSurveyRequest $request): Survey
    {
        $data = $request->validated()['data']['attributes'];

        $this->validateLegalEntityUnitRelation($data['legal_entity_id'], $data['legal_entity_unit_id']);

        $survey = Survey::create($data);

        $this->processSendersAndSpokesmen($survey, $data);

        return $survey;
    }

    private function validateLegalEntityUnitRelation(int $legalEntityId, int $unitId): void
    {
        $branch_office_id = auth()->user()->branch_id;

        $belongs = SurveyLegalEntityUnit::where('id', $unitId)
            ->where('legal_entity_id', $legalEntityId)
            ->whereHas('legal_entity', fn($q) => $q->where('branch_office_id', $branch_office_id))
            ->exists();

        abort_if((!$belongs), '422', __('validation.survey.create.legal_entity_unit_mismatch'));
    }

    private function processSendersAndSpokesmen(Survey $survey, $data): void
    { //todo: separate with a function with try catch clauses and improve
        if (isset($data['senders'])){
            $survey->senders()->createMany($data['senders']);
        }

        if (isset($data['spokesmen'])) {
            $survey->spokesmen()->createMany($data['spokesmen']);
        }

        if (isset($data['reviewer'])) {
            $survey->reviewer()->createMany($data['reviewer']);
        }
    }
}
