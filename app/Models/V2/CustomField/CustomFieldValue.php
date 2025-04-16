<?php

namespace App\Models\V2\CustomField;

use App\Enums\CustomFieldType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomFieldValue extends Model
{
    /** @use HasFactory<\Database\Factories\V2\CustomField\CustomFieldValueFactory> */
    use HasFactory;

    protected $fillable = [
        'custom_field_id',
        'model_type',
        'model_id',
        'value_string',
        'value_text',
        'value_int',
        'value_float',
        'value_boolean',
        'value_datetime',
    ];

    protected $casts = [
        'value_int' => 'integer',
        'value_float' => 'float',
        'value_boolean' => 'boolean',
        'value_datetime' => 'datetime',
    ];

    public function getValueDatetimeAttribute($value): string
    {
        return Carbon::parse($value)->format(config('app.date_format'));
    }

    public function customField()
    {
        return $this->belongsTo(CustomField::class);
    }

    public function model()
    {
        return $this->morphTo();
    }

    /**
     * @throws \Exception
     */
    public function getValueAttribute()
    {
        if ($this->type === 'multi_field') {
            throw new \BadFunctionCallException('Cannot get value of multi_field');
        }

        return match ($this->customField->type) {
            CustomFieldType::Int => $this->value_int,
            CustomFieldType::SingleChoiceEnum,
            CustomFieldType::MultipleChoiceEnum => CustomFieldOption::find($this->value_int)->label,
            CustomFieldType::Float => $this->value_float,
            CustomFieldType::Boolean => $this->value_boolean? __('etc.boolean.true'): __('etc.boolean.false'),
            CustomFieldType::Datetime => $this->value_datetime,
            CustomFieldType::Text => $this->value_text,
            default => $this->value_string, // 'string', 'enum', etc.
        };
    }

    public static function getStorableColumn(CustomFieldType $customFieldType): string
    {
        return match ($customFieldType) {
            CustomFieldType::Int,
            CustomFieldType::SingleChoiceEnum,
            CustomFieldType::MultipleChoiceEnum, => 'value_int',
            CustomFieldType::Float => 'value_float',
            CustomFieldType::String => 'value_string',
            CustomFieldType::Text => 'value_text',
            CustomFieldType::Datetime => 'value_datetime',
            CustomFieldType::Boolean => 'value_boolean',

            default => '',
        };
    }

}
