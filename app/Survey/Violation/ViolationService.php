<?php

namespace App\Survey\Violation;

use App\Document\DocumentService;
use App\Survey\Models\Survey;
use App\Survey\Violation\Http\CloseViolationRequest;
use App\Survey\Violation\Http\StoreViolationRequest;

class ViolationService extends DocumentService
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
