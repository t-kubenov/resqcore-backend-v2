<?php

namespace App\Models\V2\CustomField;

use App\Casts\CustomFieldParameterCast;
use App\Enums\CustomFieldParameter;
use App\Enums\CustomFieldType;
use App\Models\RescueCompany;
use Database\Factories\V2\CustomField\CustomFieldFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

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
        'parameters' => CustomFieldParameterCast::class,
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

//    public function getValueAttribute(): mixed
//    {
//        if (!$this->type->isComplexType()) {
//            return $this->values->;
//        }
//    }

    public function getOptionIdsAttribute(): array
    {
        return $this->options->pluck('id')->toArray();
    }

    public function generateValidationString(): array
    {
        $rules = [];

        if($this->type->isEnum()) {
            $rules[] = Rule::exists('custom_field_options', 'id')->where(function ($query) {
                $query->where('custom_field_id', $this->id);
            });
        };
        // todo: implement with actual options

        if (!empty($this->validation)){
            $rules[] = $this->validation;
        }

        return $rules;
    }

    public static function getFormattedFields(int $rescueCompanyId, string $model): array
    {
        return self::where([['rescue_company_id', $rescueCompanyId], ['model', $model]])
            ->get()
            ->mapWithKeys(function (CustomField $field) {
            return [
                $field->name => [
                    'id' => $field->id,
                    'key' => "data.attributes.{$field->name}",
                    'name' => $field->name,
                    'label' => $field->label,
                    'alias' => '',
                    'type' => $field->type,
                    'required' => $field->required,
                    'rules' => $field->generateValidationString(),
                    ...($field->type == CustomFieldType::Boolean? ['options' => route('auxiliary.field-options', 'boolean')]: []),
                    ...($field->type->isEnum()? ['options' => route('auxiliary.field-options', $field->name)]: []),
                    'parameters' => array_merge($field->parameters, [
//                        CustomFieldParameter::IsCustom,
                    ]),
                ],
            ];
        })->toArray();
    }
}
