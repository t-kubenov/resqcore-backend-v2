<?php

namespace App\Http\Requests\V2;

use App\DTOs\ReportFilterFieldValidationDTO;
use App\Models\Report;
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return ReportFilterFieldValidationDTO::fromArray(Report::filters(), 'filtering');
    }
}
