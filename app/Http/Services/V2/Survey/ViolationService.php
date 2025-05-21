<?php

namespace App\Http\Services\V2\Survey;

use App\Http\Requests\V2\Survey\CloseViolationRequest;
use App\Http\Requests\V2\Survey\StoreViolationRequest;
use App\Models\V2\Survey\Survey;
use App\Models\V2\Survey\Violation;

class ViolationService extends SurveyBaseService
{
    public function store(StoreViolationRequest $request, Survey $survey): Violation
    {
        $violation = $survey->violations()->create($request->validated()['data']['attributes']);

        return $violation;
    }

    public function close(CloseViolationRequest $request, Violation $violation): Violation
    {
        $data = $request->validated()['data']['attributes'];
        $data['elimination_completion'] = true;
        $violation->update($data);

        return $violation;
    }
}
