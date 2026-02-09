<?php

namespace App\Models\Survey;

use App\Enums\FieldParameter;
use App\Enums\FieldType;
use App\Models\Document;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;

class Violation extends Document
{
    use HasFactory;

    protected $table = 'survey_violations';

    protected $perPage = 20;

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
                'type' => FieldType::Text,
                'required' => true,
                'parameters' => [
                    FieldParameter::CategoryId->value => 1,
                    FieldParameter::IsFilterable,
                    FieldParameter::IsFilterableForCA,
                    FieldParameter::ShowInTable,
                ],
            ],

            'clause_type_id' =>
            [
                'key' => 'data.attributes.clause_type_id',
                'name' => 'clause_type_id',
                'label' => __('surveys.fields.violations.clause_type.name'),
                'alias' => __('surveys.fields.violations.clause_type.alias'),
                'type' => FieldType::SingleChoiceEnum,
                'required' => true,
                'options' => route('auxiliary.field-options', 'clause_type'),
                'parameters' => [
                    FieldParameter::CategoryId->value => 1,
                    FieldParameter::IsFilterable,
                    FieldParameter::IsFilterableForCA,
                    FieldParameter::ShowInTable,
                ],
            ],

            'clause' =>
            [
                'key' => 'data.attributes.clause',
                'name' => 'clause',
                'label' => __('surveys.fields.violations.clause.name'),
                'alias' => __('surveys.fields.violations.clause.alias'),
                'type' => FieldType::String,
                'required' => true,
                'rules' => [
                    'max:255',
                ],
                'parameters' => [
                    FieldParameter::CategoryId->value => 1,
                    FieldParameter::IsFilterable,
                    FieldParameter::IsFilterableForCA,
                    FieldParameter::ShowInTable,
                ],
            ],

            'elimination_prescription_date' =>
            [
                'key' => 'data.attributes.elimination_prescription_date',
                'name' => 'elimination_prescription_date',
                'label' => __('surveys.fields.violations.elimination_prescription_date.name'),
                'alias' => __('surveys.fields.violations.elimination_prescription_date.alias'),
                'type' => FieldType::Date,
                'required' => true,
                'parameters' => [
                    FieldParameter::CategoryId->value => 1,
                    FieldParameter::IsFilterable,
                    FieldParameter::IsFilterableForCA,
                    FieldParameter::ShowInTable,
                ],
            ],

            'elimination_completion' =>
            [
                'key' => 'data.attributes.elimination_completion',
                'name' => 'elimination_completion',
                'label' => __('surveys.fields.violations.elimination_completion.name'),
                'alias' => __('surveys.fields.violations.elimination_completion.alias'),
                'type' => FieldType::Boolean,
                'parameters' => [
                    FieldParameter::IsFilterable,
                    FieldParameter::IsFilterableForCA,
                    FieldParameter::ShowInTable,
                    FieldParameter::IsHidden,
                ],
            ],

            'elimination_completion_date' =>
            [
                'key' => 'data.attributes.elimination_completion_date',
                'name' => 'elimination_completion_date',
                'label' => __('surveys.fields.violations.elimination_completion_date.name'),
                'alias' => __('surveys.fields.violations.elimination_completion_date.alias'),
                'type' => FieldType::Date,
                'parameters' => [
                    FieldParameter::IsFilterable,
                    FieldParameter::IsFilterableForCA,
                    FieldParameter::ShowInTable,
                    FieldParameter::IsHidden,
                ],
            ],
        ];
    }

    public function getReadableData(): array
    {
        return [
            'description' => $this->description,
            'clause_type_id' => $this->clause_type->name,
            'clause_type_link' => $this->clause_type->link,
            'clause' => $this->clause,
            'elimination_prescription_date' => $this->elimination_prescription_date? $this->serializeDate($this->elimination_prescription_date): null,
            'elimination_completion' => $this->elimination_completion,
            'elimination_completion_date' => $this->elimination_completion ? $this->serializeDate($this->elimination_completion_date) : null,
        ];
    }

    public function getPrintData(): array
    {
        return [
            'survey_id' => $this->survey_id,
            'description' => $this->description,
            'clause' => $this->clause_type->name . ' - ' . $this->clause,
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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty() // only log changed fields
            ->dontSubmitEmptyLogs(); // skip if nothing changed
    }
}
