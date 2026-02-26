<?php

namespace App\Survey;

use App\Document\DTOs\ReportFilterFieldValidationDTO;
use App\Survey\Models\Survey;
use Illuminate\Foundation\Http\FormRequest;

class FilterSurveyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return ReportFilterFieldValidationDTO::fromArray(Survey::filters(), 'filtering');
    }
}
