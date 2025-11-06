<?php

namespace App\Http\Requests\CustomField;

use App\Enums\CustomFieldModel;
use App\Enums\FieldType;
use App\Models\CustomField\CustomField;
use Illuminate\Foundation\Http\FormRequest;

class StoreCustomFieldValueRequest extends FormRequest
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
        return [
            'custom_field_id' => 'required|integer|exists:custom_fields,id',
            'model_type'      => 'required|string|in:' . implode(',', CustomFieldModel::values()),
            'model_id'        => 'required|integer',
            'value'           => ['nullable', $this->getDynamicValidationRule()],
        ];
    }


    private function getDynamicValidationRule(): string
    {
        $customField = CustomField::find($this->input('custom_field_id'));

        if (!$customField) {
            return ''; // No validation if field doesn't exist
        }

        // ✅ Prevent duplicates for all plain types + single_choice_enum
        if ($customField->isPlain()) {
            return 'unique:custom_field_values,value,'
                . request('model_id') . ',model_id,custom_field_id,' . request('custom_field_id');
        }

        return match ($customField->type) {
            FieldType::Int->value => 'integer',
            FieldType::Float->value => 'numeric',
            FieldType::String->value => 'string|max:255',
            FieldType::Text->value => 'string',
            FieldType::Datetime->value => 'date',
            FieldType::Boolean->value => 'boolean',
            FieldType::SingleChoiceEnum->value, FieldType::MultipleChoiceEnum->value => 'array|exists:custom_field_options,id',
            default => 'nullable',
        };
    }
}
