<?php

namespace App\Http\Requests\Survey;

use App\DTOs\ReportFilterFieldValidationDTO;
use App\Models\Survey\Survey;
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
