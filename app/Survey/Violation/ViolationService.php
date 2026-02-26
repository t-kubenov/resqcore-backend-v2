<?php

namespace App\Survey\Violation;

use App\Document\GeneralService;
use App\Survey\Models\Survey;

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
