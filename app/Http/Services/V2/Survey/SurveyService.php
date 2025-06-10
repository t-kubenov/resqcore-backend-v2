<?php

namespace App\Http\Services\V2\Survey;

use App\Http\Requests\V2\Survey\StoreSurveyRequest;
use App\Http\Services\V2\BaseService;
use App\Models\V2\Survey\Survey;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class SurveyService extends BaseService
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
        $query = $this->filterBranchOffice($query);

        if (isset($data['branch_office_id']) && auth()->user()->can('departure_view')) {
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

        if (isset($data['legal_entity_id'])) {
            $query->whereIn('legal_entity_id', $data['legal_entity_id']);
            unset($data['legal_entity_id']);
        }

        if (isset($data['legal_entity_unit_id'])) {
            $query->whereIn('legal_entity_unit_id', $data['legal_entity_unit_id']);
            unset($data['legal_entity_unit_id']);
        }

        if (isset($data['survey_date'])) {
            $this->filterDates($query, 'survey_date', $data['survey_date']);
            unset($data['survey_date']);
        }

        if (isset($data['search'])) {
            $search = $data['search'];

            $query->whereHas('violations', function ($q) use ($search) {
                $q
                    ->where('description', 'ILIKE', "%{$search}%")
                    ->orWhere('clause', 'ILIKE', "%{$search}%");
            })
                ->orWhere('hazardous_facility_name', 'ILIKE', "%{$search}%")
                ->orWhere('survey_code', 'ILIKE', "%{$search}%");

            unset($data['search']);
        }

        return $query;
    }

    private function filterBranchOffice($query) //todo: duplicate with ReportService, should be moved to BaseService
    {
        $user = auth()->user();

        if ($user->hasRole('branch_office_user')) {
            $query->where('branch_office_id', $user->branch_id);
        }
        return $query;
    }

    private function filterDates(Builder $query, string $field, string $dates): void // todo: duplicate with ReportService, should be moved to BaseService
    {
        [$startDate, $endDate] = explode('|', $dates);

        if ($startDate) {
            $query->whereDate($field, '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate($field, '<=', $endDate);
        }
    }

    public function store(StoreSurveyRequest $request): Survey
    {
        $this->throttle($request);
        return $this->create($request);
    }

    private function create(StoreSurveyRequest $request): Survey
    {
        $data = $request->validated()['data']['attributes'];

        $survey = new Survey();
        $survey->fill($data);
        $survey->save();
        $this->processSendersAndSpokesmen($survey, $data);

        return $survey;
    }

    private function processSendersAndSpokesmen(Survey $survey, $data): void
    {
        if (isset($data['senders'])){
            $senders = array_map(fn($name) => ['name' => $name], $data['senders']);
            $survey->senders()->createMany($senders);
        }

        if (isset($data['spokesmen'])) {
            $spokesmen = array_map(fn($name) => ['name' => $name], $data['spokesmen']);
            $survey->spokesmen()->createMany($spokesmen);
        }
    }
}
