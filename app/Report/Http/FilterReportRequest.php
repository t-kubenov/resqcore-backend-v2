<?php

namespace App\Report\Http;

use App\Document\DTOs\ReportFilterFieldValidationDTO;
use App\Report\Report;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class FilterReportRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return ReportFilterFieldValidationDTO::fromArray(Report::filters(), 'filtering');
    }
}
