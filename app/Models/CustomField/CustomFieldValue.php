<?php

namespace App\Models\CustomField;

use App\Enums\FieldType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomFieldValue extends Model
{
    /** @use HasFactory<\Database\Factories\CustomField\CustomFieldValueFactory> */
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
            FieldType::Int => $this->value_int,
            FieldType::SingleChoiceEnum,
            FieldType::MultipleChoiceEnum => CustomFieldOption::find($this->value_int)->label,
            FieldType::Float => $this->value_float,
            FieldType::Boolean => $this->value_boolean? __('etc.boolean.true'): __('etc.boolean.false'),
            FieldType::Datetime => $this->value_datetime,
            FieldType::Text => $this->value_text,
            default => $this->value_string, // 'string', 'enum', etc.
        };
    }

    public static function getStorableColumn(FieldType $customFieldType): string
    {
        return match ($customFieldType) {
            FieldType::Int,
            FieldType::SingleChoiceEnum,
            FieldType::MultipleChoiceEnum, => 'value_int',
            FieldType::Float => 'value_float',
            FieldType::String => 'value_string',
            FieldType::Text => 'value_text',
            FieldType::Datetime => 'value_datetime',
            FieldType::Boolean => 'value_boolean',

            default => '',
        };
    }

}
