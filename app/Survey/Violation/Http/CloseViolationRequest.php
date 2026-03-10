<?php

namespace App\Survey\Violation\Http;

use Illuminate\Foundation\Http\FormRequest;

class CloseViolationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'data.attributes.elimination_completion_date' =>
            [
                'required',
                'date',
                'date_format:Y-m-d',
                'before_or_equal:today',
            ],
        ];
    }
}
