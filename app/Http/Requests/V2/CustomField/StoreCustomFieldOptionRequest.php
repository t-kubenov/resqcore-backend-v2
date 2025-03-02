<?php

namespace App\Http\Requests\V2\CustomField;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomFieldOptionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return array_merge([
            'custom_field_id' => ['required', 'integer', 'exists:custom_fields,id'],
            'options' => ['required', 'array', 'min:2'],
        ],
            $this->bareRules()
        );
    }

    public static function bareRules(): array
    {
        return [
            'options.*.name' => ['required', 'string'],
            'options.*.label' => ['sometimes', 'string'],
            'options.*.order' => ['sometimes', 'integer'],
        ];
    }
}
