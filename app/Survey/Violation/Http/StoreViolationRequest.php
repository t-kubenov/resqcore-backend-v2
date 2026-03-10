<?php

namespace App\Survey\Violation\Http;

use App\Document\DTOs\ReportFilterFieldValidationDTO;
use App\Survey\Violation\Violation;
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
