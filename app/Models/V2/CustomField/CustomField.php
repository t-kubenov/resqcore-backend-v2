<?php

namespace App\Models\V2\CustomField;

use App\Enums\CustomFieldType;
use App\Models\RescueCompany;
use Database\Factories\V2\CustomField\CustomFieldFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomField extends Model
{
    /** @use HasFactory<CustomFieldFactory> */
    use HasFactory;

    protected $fillable = [
        'rescue_company_id',
        'parent_field',
        'model',
        'name',
        'label',
        'required',
        'validation',
        'type',
        'parameters', // array of CustomFieldParameters
    ];

    protected $casts = [
        'type' => CustomFieldType::class,
        'parameters' => 'array',
    ];

    public function options()
    {
        return $this->hasMany(CustomFieldOption::class);
    }

    public function rescue_company()
    {
        return $this->belongsTo(RescueCompany::class);
    }

    public function values()
    {
        return $this->hasMany(CustomFieldValue::class);
    }

    public function parent_field()
    {
        return $this->belongsTo(CustomField::class);
    }

    public function getOptionIdsAttribute(): array
    {
        return $this->options->pluck('id')->toArray();
    }

    public function generateValidationString(): string
    {
        $rules = [];

        $rules[] = ($this->required? 'required': 'nullable');

        $rules[] = match ($this->type) {
            CustomFieldType::Int => 'integer',
            CustomFieldType::Float => 'numeric',
            CustomFieldType::String => 'string|max:255',
            CustomFieldType::Text => 'string',
            CustomFieldType::Datetime => 'date',
            CustomFieldType::Boolean => 'boolean',
            CustomFieldType::SingleChoiceEnum => 'integer|exists:custom_field_options,id',
            CustomFieldType::MultipleChoiceEnum => 'array|exists:custom_field_options,id',
            default => '',
        };

        $rules[] = $this->validation;

        return implode('|', array_filter($rules));
    }

    public static function getFormattedFields(int $rescueCompanyId, string $model): array
    {
        return self::where([['rescue_company_id', $rescueCompanyId], ['model', $model]])
            ->get()
            ->mapWithKeys(function (CustomField $field) {
            return [
                $field->name => [
                    'key' => "data.attributes.{$field->name}",
                    'name' => $field->name,
                    'alias' => $field->label,
                    'type' => $field->type->value,
                    'required' => $field->required,
                    'rules' => explode('|', $field->generateValidationString()),
                ],
            ];
        })->toArray();
    }
}
