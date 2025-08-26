<?php

namespace App\Http\Services\Survey;

use App\Http\Requests\Survey\CloseViolationRequest;
use App\Http\Requests\Survey\StoreViolationRequest;
use App\Http\Services\GeneralService;
use App\Models\Survey\Survey;
use App\Models\Survey\Violation;

class ViolationService extends GeneralService
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
