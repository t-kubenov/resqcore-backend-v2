<?php

namespace App\Http\Requests\V2\Survey;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
