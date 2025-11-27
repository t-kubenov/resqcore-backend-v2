<?php

namespace App\Http\Services\Survey;

use App\Http\Requests\Survey\StoreSurveyRequest;
use App\Http\Services\GeneralService;
use App\Models\Survey\Survey;
use App\Models\Survey\SurveyLegalEntityUnit;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class SurveyService extends GeneralService
{
    public function index(Request $request)
    {
        $query = Survey::query()
            ->orderBy('created_at', 'desc');

        return $this->filterQuery($request, $query)->paginate(15);
    }

    private function filterQuery(Request $request, Builder $query)
    {
        $data = $request->all();
        $query = $this->filterByRoles($query);

        if (isset($data['branch_office_id']) && auth()->user()->isCentralOffice()) {
            $query->whereIn('branch_office_id', $data['branch_office_id']);
            unset($data['branch_office_id']);
        }

        if (isset($data['created_by'])) {
            $query->whereIn('created_by', $data['created_by']);
            unset($data['created_by']);
        }

        if (isset($data['senders'])) {
            $query->whereHas('senders', function ($q) use ($data) {
                $q->where('name', 'ILIKE', "%{$data['senders']}%");
            });
            unset($data['senders']);
        }

        if (isset($data['spokesmen'])) {
            $query->whereHas('spokesmen', function ($q) use ($data) {
                $q->where('name', 'ILIKE', "%{$data['spokesmen']}%");
            });
            unset($data['spokesmen']);
        }

        if (isset($data['reviewer'])) {
            $query->whereHas('reviewer', function ($q) use ($data) {
                $q->where('name', 'ILIKE', "%{$data['reviewer']}%");
            });
            unset($data['reviewer']);
        }

        if (isset($data['survey_date'])) {
            $this->filterDates($query, 'survey_date', $data['survey_date']);
            unset($data['survey_date']);
        }

        if (isset($data['search'])) {
            $search = $data['search'];

            $query->whereHas('violations', function ($q) use ($search) {
                $q
                    ->where('description', 'ILIKE', "%$search%")
                    ->orWhere('clause', 'ILIKE', "%$search%");
            })
                ->orWhereHas('legal_entity', function ($q) use ($search) {
                    $q->where('name', 'ILIKE',  "%$search%");
                })
                ->orWhereHas('legal_entity_unit', function ($q) use ($search) {
                    $q->where('name', 'ILIKE',  "%$search%");
                })
                ->orWhere('hazardous_facility_name', 'ILIKE',  "%$search%")
                ->orWhere('survey_code', 'ILIKE',  "%$search%");

            unset($data['search']);
        }

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
