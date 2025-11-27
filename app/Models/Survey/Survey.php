<?php

namespace App\Models\Survey;

use App\Enums\FieldParameter;
use App\Enums\FieldType;
use App\Models\BranchOffice;
use App\Models\Model;
use App\Models\RescueCompany;
use App\Models\User;
use App\Observers\Survey\SurveyObserver;
use App\Traits\FormatsColumns;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

#[ObservedBy(SurveyObserver::class)]
class Survey extends Model
{
    use HasFactory, FormatsColumns;

    protected $fillable = [
        'branch_office_id',
        'created_by', //integer
        'legal_entity_id', //integer
        'legal_entity_unit_id', //integer
        'survey_code', //string, branch office number-year number-survey
        'survey_date', // datetime
        'hazardous_facility_name', //string
    ];


    public static function fieldDefinitions(): array
    {
        return [
            'survey_code' => [
                'key' => 'data.attributes.survey_code',
                'name' => 'survey_code',
                'label' => __('surveys.fields.survey_code.name'),
                'alias' => __('surveys.fields.survey_code.alias'),
                'type' => FieldType::String,
                'rules' => [
                    'max:255',
                ],
                'parameters' => [
                    FieldParameter::IsHidden,
                    FieldParameter::ShowInTable,
                    FieldParameter::ShowInCard,
                ],
            ],

            'branch_office_id' => [
                'key' => 'data.attributes.branch_office_id',
                'name' => 'branch_office_id',
                'label' => __('surveys.fields.branch_office_id.name'),
                'alias' => __('surveys.fields.branch_office_id.alias'),
                'type' => FieldType::SingleChoiceEnum,
                'options' => route('auxiliary.field-options', 'branch_office'),
                'rules' => [
                    'max:255',
                ],
                'parameters' => [
                    FieldParameter::IsHidden,
                    FieldParameter::IsFilterableForCA,
                    FieldParameter::ShowInTable,
                    FieldParameter::ShowInCard,
                ],
            ],

            'created_by' => [
                'key' => 'data.attributes.created_by',
                'name' => 'created_by',
                'label' => __('surveys.fields.created_by.name'),
                'alias' => __('surveys.fields.created_by.alias'),
                'type' => FieldType::SingleChoiceEnum,
                'options' => route('auxiliary.field-options', 'created_by'),
                'parameters' => [
                    FieldParameter::IsHidden,
                    FieldParameter::IsFilterable,
                    FieldParameter::IsFilterableForCA,
                    FieldParameter::ShowInTable,
                    FieldParameter::ShowInCard,
                ],
            ],

            'senders' =>
            [
                'key' => 'data.attributes.senders',
                'name' => 'senders',
                'label' => __('surveys.fields.senders.name'),
                'alias' => __('surveys.fields.senders.alias'),
                'type' => FieldType::MultiValue,
                'rules' => ['min:1', 'max:4'],
                'required' => true,
                'parameters' => [
                    FieldParameter::CategoryId->value => 1,
                    FieldParameter::ShowInCard,
                ],
                'subfields' => [
                    'sender_name' =>
                    [
                        'key' => 'data.attributes.senders.*.name',
                        'name' => 'senders',
                        'label' => __('surveys.fields.senders.name'),
                        'alias' => __('surveys.fields.senders.alias'),
                        'type' => FieldType::String,
                        'rules' => ['required_with:data.attributes.senders'],
                        'parameters' => [
                            FieldParameter::CategoryId->value => 1,
                            FieldParameter::IsFilterable,
                            FieldParameter::IsFilterableForCA,
                            FieldParameter::ShowInCard,
                        ],
                    ],

                    'sender_position' =>
                    [
                        'key' => 'data.attributes.senders.*.position',
                        'name' => 'senders_position',
                        'label' => __('surveys.fields.position.name'),
                        'alias' => __('surveys.fields.position.alias'),
                        'type' => FieldType::String,
                        'rules' => ['required_with:data.attributes.senders'],
                        'parameters' => [
                            FieldParameter::CategoryId->value => 1,
                            FieldParameter::ShowInCard,
                        ],
                    ],
                ]
            ],


            'spokesmen' =>
            [
                'key' => 'data.attributes.spokesmen',
                'name' => 'spokesmen',
                'label' => __('surveys.fields.spokesmen.name'),
                'alias' => __('surveys.fields.spokesmen.alias'),
                'type' => FieldType::MultiValue,
                'rules' => ['min:1', 'max:6'],
                'required' => true,
                'parameters' => [
                    FieldParameter::CategoryId->value => 1,
                    FieldParameter::IsFilterable,
                    FieldParameter::IsFilterableForCA,
                    FieldParameter::ShowInCard,
                ],
                'subfields' => [ //todo: subfields are not validated in any way, this is also probably the case for transport.
                    'spokesman_name' =>
                    [
                        'key' => 'data.attributes.spokesmen.*.name',
                        'name' => 'spokesmen',
                        'label' => __('surveys.fields.spokesmen.name'),
                        'alias' => __('surveys.fields.spokesmen.alias'),
                        'type' => FieldType::String,
                        'rules' => ['required_with:data.attributes.spokesmen'],
                        'parameters' => [
                            FieldParameter::CategoryId->value => 1,
                            FieldParameter::IsFilterable,
                            FieldParameter::IsFilterableForCA,
                            FieldParameter::ShowInCard,
                        ],
                    ],
                    'spokesman_position' =>
                    [
                        'key' => 'data.attributes.spokesmen.*.position',
                        'name' => 'spokesmen_position',
                        'label' => __('surveys.fields.position.name'),
                        'alias' => __('surveys.fields.position.alias'),
                        'type' => FieldType::String,
                        'rules' => ['required_with:data.attributes.spokesmen'],
                        'parameters' => [
                            FieldParameter::CategoryId->value => 1,
                            FieldParameter::IsFilterable,
                            FieldParameter::IsFilterableForCA,
                            FieldParameter::ShowInCard,
                        ],
                    ],
                ]
            ],

            'reviewer' =>
                [
                    'key' => 'data.attributes.reviewer',
                    'name' => 'reviewer',
                    'label' => __('surveys.fields.reviewer.name'),
                    'alias' => __('surveys.fields.reviewer.alias'),
                    'type' => FieldType::DoubleInput,
                    'rules' => ['max:1'],
                    'parameters' => [
                        FieldParameter::CategoryId->value => 1,
                        FieldParameter::IsFilterable,
                        FieldParameter::IsFilterableForCA,
                        FieldParameter::ShowInCard,
                    ],
                    'subfields' => [
                        'reviewer_name' =>
                            [
                                'key' => 'data.attributes.reviewer.*.name',
                                'name' => 'reviewer',
                                'label' => __('surveys.fields.reviewer.name'),
                                'alias' => __('surveys.fields.reviewer.alias'),
                                'type' => FieldType::String,
                                'rules' => ['required_with:data.attributes.reviewer'],
                                'parameters' => [
                                    FieldParameter::CategoryId->value => 1,
                                    FieldParameter::IsFilterable,
                                    FieldParameter::IsFilterableForCA,
                                    FieldParameter::ShowInCard,
                                ],
                            ],
                        'reviewer_position' =>
                            [
                                'key' => 'data.attributes.reviewer.*.position',
                                'name' => 'reviewer_position',
                                'label' => __('surveys.fields.position.name'),
                                'alias' => __('surveys.fields.position.alias'),
                                'type' => FieldType::String,
                                'rules' => ['required_with:data.attributes.reviewer'],
                                'parameters' => [
                                    FieldParameter::CategoryId->value => 1,
                                    FieldParameter::IsFilterable,
                                    FieldParameter::IsFilterableForCA,
                                    FieldParameter::ShowInCard,
                                ],
                            ],
                    ]
                ],

            'legal_entity_id' => [
                'key' => 'data.attributes.legal_entity_id',
                'name' => 'legal_entity_id',
                'label' => __('surveys.fields.legal_entity_id.name'),
                'alias' => __('surveys.fields.legal_entity_id.alias'),
                'type' => FieldType::SingleChoiceEnum,
                'required' => true,
                'rules' => [
                    'exists:legal_entities,id',
                ],
                'options' => route('auxiliary.field-options', 'legal_entity'),
                'parameters' => [
                    FieldParameter::CategoryId->value => 1,
                    FieldParameter::ShowInTable,
                    FieldParameter::ShowInCard,
                ],
            ],

            'legal_entity_unit_id' => [
                'key' => 'data.attributes.legal_entity_unit_id',
                'name' => 'legal_entity_unit_id',
                'label' => __('surveys.fields.legal_entity_unit_id.name'),
                'alias' => __('surveys.fields.legal_entity_unit_id.alias'),
                'type' => FieldType::SingleChoiceEnum,
                'required' => true,
                'rules' => [
                    'exists:legal_entity_units,id',
                ],
                'options' => route('auxiliary.field-options', 'legal_entity_unit'),
                'parameters' => [
                    FieldParameter::CategoryId->value => 1,
                    FieldParameter::FilterByField->value => 'legal_entity_id',
                    FieldParameter::ShowInTable,
                    FieldParameter::ShowInCard,
                ],
            ],

            'survey_date' => [
                'key' => 'data.attributes.survey_date',
                'name' => 'survey_date',
                'label' => __('surveys.fields.survey_date.name'),
                'alias' => __('surveys.fields.survey_date.alias'),
                'type' => FieldType::Datetime,
                'required' => true,
                'parameters' => [
                    FieldParameter::CategoryId->value => 1,
                    FieldParameter::IsFilterable,
                    FieldParameter::IsFilterableForCA,
                    FieldParameter::ShowInTable,
                    FieldParameter::ShowInCard,
                ],
            ],

            'hazardous_facility_name' => [
                'key' => 'data.attributes.hazardous_facility_name',
                'name' => 'hazardous_facility_name',
                'label' => __('surveys.fields.hazardous_facility_name.name'),
                'alias' => __('surveys.fields.hazardous_facility_name.alias'),
                'type' => FieldType::String,
                'rules' => [
                    'max:255',
                ],
                'required' => true,
                'parameters' => [
                    FieldParameter::CategoryId->value => 1,
                    FieldParameter::ShowInTable,
                    FieldParameter::ShowInCard,
                ],
            ],
        ];
    }

    public function getReadableData(): array
    {
        return [
            'survey_code' => $this->survey_code,
            'branch_office_id' => $this->branch_office->name,
            'created_by' => $this->user->full_name,
            'senders' => $this->senders->select('name', 'position'),
            'spokesmen' => $this->spokesmen->select('name', 'position'),
            'reviewer' => $this->reviewer->select('name', 'position'),
            'legal_entity_id' => $this->legal_entity->name,
            'legal_entity_unit_id' => $this->legal_entity_unit->name,
            'survey_date' => $this->survey_date,
            'hazardous_facility_name' => $this->hazardous_facility_name,
        ];
    }

    public function getPrintData(): array
    {
        return [
            'branch_office_id' => $this->branch_office_id,
            'created_by' => $this->user->full_name,
            'senders' => $this->senders->select('name', 'position'),
            'spokesmen' => $this->spokesmen->select('name', 'position'),
            'reviewer' => $this->reviewer->select('name', 'position'),
            'legal_entity_id' => $this->legal_entity->name,
            'legal_entity_unit_id' => $this->legal_entity_unit->name,
            'survey_date' => Carbon::parse($this->survey_date)->format('Y-m-d'),
            'hazardous_facility_name' => $this->hazardous_facility_name,
        ];
    }

    public static function generatePrintColumns(): array
    {
        return collect(\Lang::get('surveys.print', [], 'ru'))
            ->map(fn($group) => collect($group)
                ->map(fn($value, $key) => ['key' => $key] + $value)
                ->values()
            )
            ->toArray();
    }

    public function branch_office(): BelongsTo
    {
        return $this->belongsTo(BranchOffice::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function senders(): HasMany
    {
        return $this->hasMany(SurveySender::class);
    }

    public function spokesmen(): HasMany
    {
        return $this->hasMany(SurveyFacilitySpokesman::class);
    }

    public function reviewer(): HasMany
    {
        return $this->hasMany(SurveyReviewer::class);
    }

    public function violations(): HasMany
    {
        return $this->hasMany(Violation::class);
    }

    public function legal_entity(): BelongsTo
    {
        return $this->belongsTo(SurveyLegalEntity::class);
    }

    public function legal_entity_unit(): BelongsTo
    {
        return $this->belongsTo(SurveyLegalEntityUnit::class);
    }

    public function rescue_company(): HasOneThrough
    {
        return $this->hasOneThrough(RescueCompany::class, BranchOffice::class, localKey: 'branch_id');
    }

}
