<?php

namespace App\Http\Requests\V2\Survey;

use App\DTOs\ReportFilterFieldValidationDTO;
use App\Models\V2\Survey\Violation;
use Illuminate\Foundation\Http\FormRequest;

class StoreViolationRequest extends FormRequest
{
    public function rules():array
    {
        return $this->getRules(new Violation());
    }

    protected function getRules(Violation $survey): array
    {
        return ReportFilterFieldValidationDTO::fromArray($survey->fieldDefinitions(), 'creation');
    }
}
