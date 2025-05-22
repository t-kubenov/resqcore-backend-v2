<?php

namespace App\Http\Services\V2\Survey;

use App\Http\Requests\V2\Survey\StoreSurveyRequest;
use App\Models\V2\Survey\Survey;
use Illuminate\Http\Request;

class SurveyService extends SurveyBaseService
{
    public function index(Request $request)
    {
        $query = Survey::query();

        return $query->paginate();
    }

    public function show(Survey $survey)
    {

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
