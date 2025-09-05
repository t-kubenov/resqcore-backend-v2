<?php

namespace App\Models\Survey;

use App\Enums\CustomFieldParameter;
use App\Enums\CustomFieldType;
use App\Models\Model;
use App\Traits\FormatsColumns;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Violation extends Model
{
    use HasFactory, FormatsColumns;

    protected $table = 'survey_violations';

    protected $hidden = [
        'created_at',
        'updated_at',
        'id',
    ];

    protected $fillable = [
        'survey_id',
        'description', //text
        'clause_type_id',
        'clause', //text? string?
        'elimination_prescription_date', //date, no time
        'elimination_completion',  //boolean
        'elimination_completion_date', //date, no time
    ];

    protected $casts = [
        'elimination_prescription_date' => 'date',
        'elimination_completion_date' => 'date',
        'elimination_completion' => 'boolean',
    ];

    protected $attributes = [
        'elimination_completion' => false,
    ];

    public static function fieldDefinitions(): array
    {
        return [
            'description' =>
            [
                'key' => 'data.attributes.description',
                'name' => 'description',
                'label' => __('surveys.fields.violations.description.name'),
                'alias' => __('surveys.fields.violations.description.alias'),
                'type' => CustomFieldType::Text,
                'required' => true,
                'parameters' => [
                    CustomFieldParameter::CategoryId->value => 1,
                    CustomFieldParameter::IsFilterable,
                    CustomFieldParameter::IsFilterableForCA,
                    CustomFieldParameter::ShowInTable,
                    CustomFieldParameter::ShowInCard,
                ],
            ],

            'clause_type_id' =>
                [
                    'key' => 'data.attributes.clause_type_id',
                    'name' => 'clause_type_id',
                    'label' => __('surveys.fields.violations.clause_type.name'),
                    'alias' => __('surveys.fields.violations.clause_type.alias'),
                    'type' => CustomFieldType::SingleChoiceEnum,
                    'required' => true,
                    'options' => route('auxiliary.field-options', 'clause_type'),
                    'parameters' => [
                        CustomFieldParameter::CategoryId->value => 1,
                        CustomFieldParameter::IsFilterable,
                        CustomFieldParameter::IsFilterableForCA,
                        CustomFieldParameter::ShowInTable,
                        CustomFieldParameter::ShowInCard,
                    ],
                ],

            'clause' =>
            [
                'key' => 'data.attributes.clause',
                'name' => 'clause',
                'label' => __('surveys.fields.violations.clause.name'),
                'alias' => __('surveys.fields.violations.clause.alias'),
                'type' => CustomFieldType::String,
                'required' => true,
                'rules' => [
                    'max:255',
                ],
                'parameters' => [
                    CustomFieldParameter::CategoryId->value => 1,
                    CustomFieldParameter::IsFilterable,
                    CustomFieldParameter::IsFilterableForCA,
                    CustomFieldParameter::ShowInTable,
                    CustomFieldParameter::ShowInCard,
                ],
            ],

            'elimination_prescription_date' =>
            [
                'key' => 'data.attributes.elimination_prescription_date',
                'name' => 'elimination_prescription_date',
                'label' => __('surveys.fields.violations.elimination_prescription_date.name'),
                'alias' => __('surveys.fields.violations.elimination_prescription_date.alias'),
                'type' => CustomFieldType::Date,
                'required' => true,
                'parameters' => [
                    CustomFieldParameter::CategoryId->value => 1,
                    CustomFieldParameter::IsFilterable,
                    CustomFieldParameter::IsFilterableForCA,
                    CustomFieldParameter::ShowInTable,
                    CustomFieldParameter::ShowInCard,
                ],
            ],

            'elimination_completion' =>
            [
                'key' => 'data.attributes.elimination_completion',
                'name' => 'elimination_completion',
                'label' => __('surveys.fields.violations.elimination_completion.name'),
                'alias' => __('surveys.fields.violations.elimination_completion.alias'),
                'type' => CustomFieldType::Boolean,
                'parameters' => [
                    CustomFieldParameter::IsFilterable,
                    CustomFieldParameter::IsFilterableForCA,
                    CustomFieldParameter::ShowInTable,
                    CustomFieldParameter::IsHidden,
                    CustomFieldParameter::ShowInCard,
                ],
            ],

            'elimination_completion_date' =>
            [
                'key' => 'data.attributes.elimination_completion_date',
                'name' => 'elimination_completion_date',
                'label' => __('surveys.fields.violations.elimination_completion_date.name'),
                'alias' => __('surveys.fields.violations.elimination_completion_date.alias'),
                'type' => CustomFieldType::Date,
                'parameters' => [
                    CustomFieldParameter::IsFilterable,
                    CustomFieldParameter::IsFilterableForCA,
                    CustomFieldParameter::ShowInTable,
                    CustomFieldParameter::IsHidden,
                    CustomFieldParameter::ShowInCard,
                ],
            ],
        ];
    }

    public function getReadableData(): array
    {
        return [
            'description' => $this->description,
            'clause_type' => $this->clause_type->only(['name', 'link']),
            'clause' => $this->clause,
            'elimination_prescription_date' => Carbon::parse($this->elimination_prescription_date)->format('Y-m-d'),
            'elimination_completion' => $this->elimination_completion,
            'elimination_completion_date' => $this->elimination_completion ? Carbon::parse($this->elimination_completion_date)->format('Y-m-d') : null,
        ];
    }

    public function survey(): BelongsTo
    {
        return $this->belongsTo(Survey::class);
    }

    public function clause_type(): BelongsTo
    {
        return $this->belongsTo(ViolationClauseType::class);
    }
}
