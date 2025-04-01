<?php

namespace App\Models;

use App\Enums\CustomFieldType;
use App\Models\V2\CustomField\CustomField;
use App\Models\V2\CustomField\CustomFieldValue;
use App\Traits\AltersDisplayedDates;
use Illuminate\Validation\Rule;


abstract class BaseOperation extends Model
{
    use AltersDisplayedDates;

    protected $fillable = [
        'minor_injury_count',
        'severe_injury_count',
        'fatal_injury_count',
        'evacuated_people_count', // ex people_evacuated
        'provided_medical_count',
        'client_equipment_count', // ex customer_technology
        'third_parties_count', // ex party_authority other organizations involved
        'branch_worker_count', // ex branch_workers varchar

        'work_scope', // ex scope_work
        'situation_during_filing', // ex situation_filing
        'worker_man_hours', // used to be merged with branch worker count
        'close_date', // ex finish_work_date
        'reported_by'
    ];

    protected function casts(): array
    {
        return [
            'close_date' => 'datetime',
        ];
    }


    public static function fieldDefinitions(): array
    {
        return array_merge(CustomField::getFormattedFields(auth()->user()->rescue_company_id, static::class), [
            'work_scope' => [
                'key' => 'data.attributes.work_scope',
                'name' => __('reports.fields.work_scope.name'),
                'alias' => __('reports.fields.work_scope.alias'),
                'type' => CustomFieldType::Text,
                'required' => true,
            ],
            'situation_during_filing' => [
                'key' => 'data.attributes.situation_during_filing',
                'name' => __('reports.fields.situation_during_filing.name'),
                'alias' => __('reports.fields.situation_during_filing.alias'),
                'type' => CustomFieldType::Text,
                'required' => false,
            ],
            'branch_worker_count' => [
                'key' => 'data.attributes.branch_worker_count',
                'name' => __('reports.fields.branch_worker_count.name'),
                'alias' => __('reports.fields.branch_worker_count.alias'),
                'type' => CustomFieldType::Int,
                'required' => true,
                'rules' => ['min:1', 'max:10000'],
            ],
            'minor_injury_count' => [
                'key' => 'data.attributes.minor_injury_count',
                'name' => __('reports.fields.minor_injury_count.name'),
                'alias' => __('reports.fields.minor_injury_count.alias'),
                'type' => CustomFieldType::Int,
                'required' => true,
                'rules' => ['min:0', 'max:10000'],
            ],
            'severe_injury_count' => [
                'key' => 'data.attributes.severe_injury_count',
                'name' => __('reports.fields.severe_injury_count.name'),
                'alias' => __('reports.fields.severe_injury_count.alias'),
                'type' => CustomFieldType::Int,
                'required' => true,
                'rules' => ['min:0', 'max:10000'],
            ],
            'fatal_injury_count' => [
                'key' => 'data.attributes.fatal_injury_count',
                'name' => __('reports.fields.fatal_injury_count.name'),
                'alias' => __('reports.fields.fatal_injury_count.alias'),
                'type' => CustomFieldType::Int,
                'required' => true,
                'rules' => ['min:0', 'max:10000'],
            ],
            'evacuated_people_count' => [
                'key' => 'data.attributes.evacuated_people_count',
                'name' => __('reports.fields.evacuated_people_count.name'),
                'alias' => __('reports.fields.evacuated_people_count.alias'),
                'type' => CustomFieldType::Int,
                'required' => true,
                'rules' => ['min:0', 'max:10000'],
            ],
            'provided_medical_count' => [
                'key' => 'data.attributes.provided_medical_count',
                'name' => __('reports.fields.provided_medical_count.name'),
                'alias' => __('reports.fields.provided_medical_count.alias'),
                'type' => CustomFieldType::Int,
                'required' => true,
                'rules' => ['min:0', 'max:10000'],
            ],
            'client_equipment_count' => [
                'key' => 'data.attributes.client_equipment_count',
                'name' => __('reports.fields.client_equipment_count.name'),
                'alias' => __('reports.fields.client_equipment_count.alias'),
                'type' => CustomFieldType::Int,
                'required' => true,
                'rules' => ['min:0', 'max:10000'],
            ],
            'third_parties_count' => [
                'key' => 'data.attributes.third_parties_count',
                'name' => __('reports.fields.third_parties_count.name'),
                'alias' => __('reports.fields.third_parties_count.alias'),
                'type' => CustomFieldType::Int,
                'required' => true,
                'rules' => ['min:0', 'max:10000'],
            ],
            'worker_man_hours' => [
                'key' => 'data.attributes.worker_man_hours',
                'name' => __('reports.fields.worker_man_hours.name'),
                'alias' => __('reports.fields.worker_man_hours.alias'),
                'type' => CustomFieldType::Float,
                'required' => false,
                'rules' => [
                    'required_with:data.attributes.close_date',
                    'min:0',
                    'max:1000000'
                ],
            ],
            'close_date' => [
                'key' => 'data.attributes.close_date',
                'name' => __('reports.fields.close_date.name'),
                'alias' => __('reports.fields.close_date.alias'),
                'type' => CustomFieldType::Datetime,
                'required' => false,
                'rules' => [
                    'required_with:data.attributes.worker_man_hours',
                    'after_or_equal:data.attributes.work_start_date',
                    'before_or_equal:tomorrow',
                ],
            ],
            'transport_id' => [
                'key' => 'data.attributes.transport.*.id',
                'name' => __('reports.fields.transport_id.name'),
                'alias' => __('reports.fields.transport_id.alias'),
                'type' => CustomFieldType::Int,
                'required' => true,
                'rules' => [
                    Rule::exists('transport_types', 'id')->where(function ($query) {
                        $query->where('rescue_company_id', auth()->user()->branch_office->rescue_company_id);
                    })
                ],
                'options' => route('auxiliary.field-options', 'transport-types'),
            ],
            'transport_count' => [
                'key' => 'data.attributes.transport.*.count',
                'name' => __('reports.fields.transport_count.name'),
                'alias' => __('reports.fields.transport_count.alias'),
                'type' => CustomFieldType::Int,
                'required' => true,
                'rules' => ['min:1', 'max:10000'],
            ],
        ]);
    }


    public function reporter()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    public function transport()
    {
        return $this->hasMany(Transport::class);
    }


    public static function customFieldNames(): array
    {
        return CustomField::where([
            ['model', static::class],
            ['rescue_company_id', auth()->user()->rescue_company_id]
        ])->pluck('name')
            ->toArray();
    }

    public function customFieldValues()
    {
        return $this->morphMany(CustomFieldValue::class, 'model');
    }

    public function customFieldValueArray(): array
    {
        return $this->customFieldValues->mapWithKeys(function (CustomFieldValue $value) {
            return [$value->customField->name => $value->value];
        })->toArray();
    }
}
