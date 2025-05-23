<?php

namespace App\Models\V2\Survey;

use App\Enums\CustomFieldParameter;
use App\Enums\CustomFieldType;
use App\Models\BranchOffice;
use App\Models\User;
use App\Observers\Survey\SurveyObserver;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Validation\Rule;

#[ObservedBy(SurveyObserver::class)]
class Survey extends Model
{
    use HasFactory;

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
                'type' => CustomFieldType::String,
                'rules' => [
                    'max:255',
                ],
                'parameters' => [
                    CustomFieldParameter::IsHidden,
                    CustomFieldParameter::ShowInTable,
                    CustomFieldParameter::ShowInCard,
                ],
            ],

            'branch_office_id' => [
                'key' => 'data.attributes.branch_office_id',
                'name' => 'branch_office_id',
                'label' => __('surveys.fields.branch_office_id.name'),
                'alias' => __('surveys.fields.branch_office_id.alias'),
                'type' => CustomFieldType::String,
                'options' => route('auxiliary.field-options', 'branch_office_id'),
                'rules' => [
                    'max:255',
                ],
                'parameters' => [
                    CustomFieldParameter::IsHidden,
                    CustomFieldParameter::IsFilterable,
                    CustomFieldParameter::IsFilterableForCA,
                    CustomFieldParameter::ShowInTable,
                    CustomFieldParameter::ShowInCard,
                ],
            ],

            'created_by' => [
                'key' => 'data.attributes.created_by',
                'name' => 'created_by',
                'label' => __('surveys.fields.created_by.name'),
                'alias' => __('surveys.fields.created_by.alias'),
                'type' => CustomFieldType::Int,
                'options' => route('auxiliary.field-options', 'created_by'),
                'parameters' => [
                    CustomFieldParameter::IsHidden,
                    CustomFieldParameter::IsFilterable,
                    CustomFieldParameter::IsFilterableForCA,
                    CustomFieldParameter::ShowInTable,
                    CustomFieldParameter::ShowInCard,
                ],
            ],

            'senders' =>
            [
                'key' => 'data.attributes.senders',
                'name' => 'senders',
                'label' => __('surveys.fields.senders.name'),
                'alias' => __('surveys.fields.senders.alias'),
                'type' => CustomFieldType::StringArray,
                'rules' => ['array', 'max:4'],
                'parameters' => [
                    CustomFieldParameter::IsFilterable,
                    CustomFieldParameter::IsFilterableForCA,
                    CustomFieldParameter::ShowInCard,
                ],
            ],

            'spokesmen' =>
            [
                'key' => 'data.attributes.spokesmen',
                'name' => 'spokesmen',
                'label' => __('surveys.fields.spokesmen.name'),
                'alias' => __('surveys.fields.spokesmen.alias'),
                'type' => CustomFieldType::StringArray,
                'rules' => ['array', 'min:1', 'max:6'],
                'parameters' => [
                    CustomFieldParameter::IsFilterable,
                    CustomFieldParameter::IsFilterableForCA,
                    CustomFieldParameter::ShowInCard,
                ],
            ],

            'legal_entity_id' => [
                'key' => 'data.attributes.legal_entity_id',
                'name' => 'legal_entity_id',
                'label' => __('surveys.fields.legal_entity_id.name'),
                'alias' => __('surveys.fields.legal_entity_id.alias'),
                'type' => CustomFieldType::SingleChoiceEnum,
                'required' => true,
                'rules' => [
                    'exists:legal_entities,id',
                ],
                'options' => route('auxiliary.field-options', 'legal_entity'),
                'parameters' => [
                    CustomFieldParameter::IsFilterable,
                    CustomFieldParameter::IsFilterableForCA,
                    CustomFieldParameter::ShowInTable,
                    CustomFieldParameter::ShowInCard,
                ],
            ],

            'legal_entity_unit_id' => [
                'key' => 'data.attributes.legal_entity_unit_id',
                'name' => 'legal_entity_unit_id',
                'label' => __('surveys.fields.legal_entity_unit_id.name'),
                'alias' => __('surveys.fields.legal_entity_unit_id.alias'),
                'type' => CustomFieldType::SingleChoiceEnum,
                'required' => true,
                'rules' => [
                    'exists:legal_entity_units,id',
                ],
                'options' => route('auxiliary.field-options', 'legal_entity_unit'),
                'parameters' => [
                    CustomFieldParameter::IsFilterable,
                    CustomFieldParameter::IsFilterableForCA,
                    CustomFieldParameter::ShowInTable,
                    CustomFieldParameter::ShowInCard,
                ],
            ],

            'survey_date' => [
                'key' => 'data.attributes.survey_date',
                'name' => 'survey_date',
                'label' => __('surveys.fields.survey_date.name'),
                'alias' => __('surveys.fields.survey_date.alias'),
                'type' => CustomFieldType::Datetime,
                'required' => true,
                'parameters' => [
                    CustomFieldParameter::IsFilterable,
                    CustomFieldParameter::IsFilterableForCA,
                    CustomFieldParameter::ShowInTable,
                    CustomFieldParameter::ShowInCard,
                ],
            ],

            'hazardous_facility_name' => [
                'key' => 'data.attributes.hazardous_facility_name',
                'name' => 'hazardous_facility_name',
                'label' => __('surveys.fields.hazardous_facility_name.name'),
                'alias' => __('surveys.fields.hazardous_facility_name.alias'),
                'type' => CustomFieldType::String,
                'rules' => [
                    'max:255',
                ],
                'required' => true,
                'parameters' => [
                    CustomFieldParameter::ShowInTable,
                    CustomFieldParameter::ShowInCard,
                ],
            ],
        ];
    }

    public static function columns(): array
    {
        return array_values(array_map(
            fn($field) => [
                'key' => $field['name'],
                'name' => $field['label'],
                'alias' => $field['alias'],
            ],
            array_filter(self::fieldDefinitions(), fn($field) =>
                in_array(CustomFieldParameter::ShowInTable, $field['parameters'])
            )
        ));
    }

    public static function cardColumns(): array
    {
        return array_values(array_map(
            fn($field) => [
                'key' => $field['name'],
                'name' => $field['label'],
                'alias' => $field['alias'],
            ],
            array_filter(self::fieldDefinitions(), fn($field) =>
            in_array(CustomFieldParameter::ShowInCard, $field['parameters'])
            )
        ));
    }

    public function getReadableData(): array
    {
        return [
            'survey_code' => $this->survey_code,
            'branch_office_id' => $this->branch_office->name,
            'created_by' => $this->user->full_name,
            'senders' => $this->senders->pluck('name'),
            'spokesmen' => $this->spokesmen->pluck('name'),
            'legal_entity_id' => $this->legal_entity->name,
            'legal_entity_unit_id' => $this->legal_entity_unit->name,
            'survey_date' => Carbon::parse($this->survey_date)->format('Y-m-d'),
            'hazardous_facility_name' => $this->hazardous_facility_name, // maybe: move to mutators?
        ];
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

}
