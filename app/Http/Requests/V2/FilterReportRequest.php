<?php

namespace App\Http\Requests\V2;

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
        return [
            'emergency_type'                => ['sometimes', 'array', 'min:1'],
            'emergency_type.*'              => ['integer'],
            'branch_office_id'              => ['sometimes', 'array', 'min:1'],
            'branch_office_id.*'            => ['integer'],
            'is_emergency_line'             => ['sometimes', 'boolean'],
            'date_start'                    => ['sometimes', 'date'],
            'date_end'                      => ['sometimes', 'date'],
            'search'                        => ['sometimes', 'string', 'max:255'],
            'is_closed'                     => ['sometimes', 'boolean'],
        ];
    }
}
