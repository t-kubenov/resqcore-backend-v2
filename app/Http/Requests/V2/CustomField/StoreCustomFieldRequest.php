<?php

namespace App\Http\Requests\V2\CustomField;

use App\Enums\CustomFieldModel;
use App\Enums\CustomFieldParameter;
use App\Enums\CustomFieldType;
use Illuminate\Foundation\Http\FormRequest;

class StoreCustomFieldRequest extends FormRequest
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
        $modelValues = implode(',', CustomFieldModel::values());
        $typeValues = implode(',', CustomFieldType::values());
        $parameterValues = implode(',', CustomFieldParameter::values());

        return array_merge([
            'rescue_company_id'        => ['required', 'integer', 'exists:rescue_companies,id'],
            'parent_field'             => ['nullable', 'integer', 'exists:custom_fields,id'],
            'name'                     => ['required', 'string'],
            'label'                    => ['nullable', 'string'],
            'model'                    => ['required', 'string', "in:$modelValues"],
            'required'                 => ['required', 'boolean'], // is set to false by db if not provided
            'validation'               => ['nullable', 'string'],
            'type'                     => ['required', "in:$typeValues"],
            'parameters'               => ['sometimes', 'array'],
            'parameters.*'             => ['sometimes', 'string', "in:$parameterValues"],
            'options'                  => ['array', 'min:2', 'required_if:type,' .
                CustomFieldType::SingleChoiceEnum->value . ',' . CustomFieldType::MultipleChoiceEnum->value
            ],
            'subfields'         => ['array', 'min:2', 'required_if:type,' . CustomFieldType::MultiValue->value],
        ],
            StoreCustomFieldOptionRequest::bareRules(),
            self::subFieldRules()
        );
    }

    protected static function subFieldRules(): array
    {
        return [
            'subfields.*.name'  => ['required', 'string'],
            'subfields.*.label' => ['required', 'string'],
            'subfields.*.type'  => ['required', 'in:' . implode(',', CustomFieldType::values()),
                'prohibited_if:subfields.*.type,' . CustomFieldType::MultiValue->value // MultiValue can't have subfields
            ],
            'subfields.*.required' => ['required', 'boolean'],
            'subfields.*.parent_field' => ['required', 'integer', 'exists:custom_fields,id'],
            'subfields.*.options' => [
                'sometimes',
                'array',
                'min:2',
                'required_if:subfields.*.type,' . CustomFieldType::SingleChoiceEnum->value . ',' . CustomFieldType::MultipleChoiceEnum->value
            ],
        ];
    }
}
