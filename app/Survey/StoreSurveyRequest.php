<?php

namespace App\Survey;

use App\Document\DTOs\ReportFilterFieldValidationDTO;
use App\Survey\Models\Survey;
use Illuminate\Foundation\Http\FormRequest;

class StoreSurveyRequest extends FormRequest
{
    public function rules():array
    {
        return $this->getRules(new Survey());
    }

    protected function getRules(Survey $survey): array
    {
        return ReportFilterFieldValidationDTO::fromArray($survey->fieldDefinitions(), 'creation');
    }
}
