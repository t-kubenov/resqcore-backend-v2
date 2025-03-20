<?php

namespace App\Models;

use App\Enums\AccessLevel;
use App\Enums\CustomFieldParameter;
use App\Enums\CustomFieldType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Validation\Rule;


class Report extends BaseOperation
{
    use HasFactory;

    public function __construct(array $attributes = []){
        parent::__construct($attributes);

        $this->mergeFillable([
            'branch_office_id',
            'facility_owner_id',
            'report_code',
            'caller',

            'line_facility_owner',
            'emergency_location',
            'emergency_info',
            'call_date',
            'work_start_date',
            'emergency_type',
            'other_info',

            'minor_injury_total',
            'severe_injury_total',
            'fatal_injury_total',
            'evacuated_people_total',
            'provided_medical_total',
            'client_equipment_total',
            'third_parties_total',
            'branch_worker_total',

            'is_emergency_line',
            'is_closed',

            'last_update_created_at'
        ]);
    }

    public static function fieldDefinitions(): array
    {
        return array_merge([
            'branch_worker_count' => [
                'key' => 'data.attributes.branch_worker_count',
                'name' => __('reports.fields.branch_worker_count.name'),
                'alias' => __('reports.fields.branch_worker_count.alias'),
                'type' => CustomFieldType::Int,
                'required' => true,
                'rules' => ['min:1', 'max:10000'],
            ],
            'branch_office_id' => [
                'key' => 'data.attributes.branch_office_id',
                'name' => __('reports.fields.branch_office_id.name'),
                'alias' => __('reports.fields.branch_office_id.alias'),
                'type' => CustomFieldType::SingleChoiceEnum,
                'rules' => ['exists:branch_offices,id'],
                'required' => false,
                'options' => route('auxiliary.field-options', 'branch-offices'),
                'parameters' => [
                    CustomFieldParameter::IsFilterableForCA
                ]
            ],
            'caller' => [
                'key' => 'data.attributes.caller',
                'name' => __('reports.fields.caller.name'),
                'alias' => __('reports.fields.caller.alias'),
                'type' => CustomFieldType::String,
                'required' => true,
            ],
            'call_date' => [
                'key' => 'data.attributes.call_date',
                'name' => __('reports.fields.call_date.name'),
                'alias' => __('reports.fields.call_date.alias'),
                'type' => CustomFieldType::Datetime,
                'required' => true,
                'rules' => [
                    'before_or_equal:data.attributes.work_start_date',
                    'before_or_equal:tomorrow',
                ],
                'parameters' => [
                    CustomFieldParameter::IsFilterable,
                    CustomFieldParameter::IsFilterableForCA
                ]
            ],
            'work_start_date' => [
                'key' => 'data.attributes.work_start_date',
                'name' => __('reports.fields.work_start_date.name'),
                'alias' => __('reports.fields.work_start_date.alias'),
                'type' => CustomFieldType::Datetime,
                'required' => true,
                'rules' => [
                    'after_or_equal:data.attributes.call_date',
                    'before_or_equal:tomorrow',
                ],
            ],
            'emergency_type_id' => [
                'key' => 'data.attributes.emergency_type.id',
                'name' => __('reports.fields.emergency_type.name'),
                'alias' => __('reports.fields.emergency_type.alias'),
                'type' => CustomFieldType::SingleChoiceEnum,
                'required' => true,
                'rules' => [
                    Rule::exists('emergency_types', 'id')->where(function ($query) {
                        $query->where('rescue_company_id', auth()->user()->rescue_company->id);
                    })
                ],
                'options' => route('auxiliary.field-options', 'emergency-types'),
                'parameters' => [
                    CustomFieldParameter::IsFilterable,
                    CustomFieldParameter::IsFilterableForCA
                ]
            ],
            'is_emergency_line' => [
                'key' => 'data.attributes.is_emergency_line',
                'name' => __('reports.fields.is_emergency_line.name'),
                'alias' => __('reports.fields.is_emergency_line.alias'),
                'type' => CustomFieldType::SingleChoiceEnum,
                'required' => true,
                'options' => route('auxiliary.field-options', 'departure-types'),
                'parameters' => [
                    CustomFieldParameter::IsFilterable,
                    CustomFieldParameter::IsFilterableForCA
                ]
            ],
            'line_facility_owner' => [
                'key' => 'data.attributes.line_facility_owner',
                'name' => __('reports.fields.line_facility_owner.name'),
                'alias' => __('reports.fields.line_facility_owner.alias'),
                'type' => CustomFieldType::String,
                'rules' => ['required', 'string'],
            ],
            'emergency_location' => [
                'key' => 'data.attributes.emergency_location',
                'name' => __('reports.fields.emergency_location.name'),
                'alias' => __('reports.fields.emergency_location.alias'),
                'type' => CustomFieldType::String,
                'rules' => ['required', 'string'],
            ],
            'emergency_info' => [
                'key' => 'data.attributes.emergency_info',
                'name' => __('reports.fields.emergency_info.name'),
                'alias' => __('reports.fields.emergency_info.alias'),
                'type' => CustomFieldType::Text,
                'rules' => ['nullable', 'string'],
            ],
            'transport' => [
                'key' => 'data.attributes.transport',
                'name' => __('reports.fields.transport.name'),
                'alias' => __('reports.fields.transport.alias'),
                'type' => CustomFieldType::MultiValue,
                'rules' => ['required', 'array', 'min:1'],
            ],
            'is_closed' => [
                'key' => 'data.attributes.is_closed',
                'name' => __('reports.fields.is_closed.name'),
                'alias' => __('reports.fields.is_closed.alias'),
                'type' => CustomFieldType::Boolean,
                'parameters' => [
                    CustomFieldParameter::IsFilterable,
                    CustomFieldParameter::IsHidden,
                    CustomFieldParameter::IsFilterableForCA
                ]
            ]
        ], parent::fieldDefinitions());

    }

    public static function filters(): array
    {

        return array_filter(Report::fieldDefinitions(), function ($field) {

            $needle = auth()->user()->access_level === AccessLevel::BRANCH?
                CustomFieldParameter::IsFilterable: CustomFieldParameter::IsFilterableForCA;

            return in_array($needle, $field['parameters'] ?? []);
        });
    }

    protected function casts(): array
    {
        return [
            'call_date' => 'datetime',
            'work_start_date' => 'datetime',
            'last_update_created_at' => 'datetime',
            'close_date' => 'datetime',
        ];
    }

    public function rescue_company()
    {
        return $this->belongsToThrough(
            RescueCompany::class,
            BranchOffice::class,
        );
    }

    public function emergency()
    {
        return $this->belongsTo(EmergencyType::class, 'emergency_type');
    }

    public function emergency_name()
    {
        return $this->emergency->name;
    }

    public function updates()
    {
        return $this->hasMany(Update::class, 'report_id');
    }

    public function facility_owner()
    {
        return $this->belongsTo(Company::class, 'facility_owner_id');
    }

    public function branch_office()
    {
        return $this->belongsTo(BranchOffice::class, 'branch_office_id');
    }

    public function transportTotals(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->groupAndSumTransport()
        );
    }

    /**
     * Group and sum transport data by 'name'.
     *
     * @return array
     */
    protected function groupAndSumTransport(): array
    {
        $updateTransports = $this->updates->flatMap(fn($update) => $update->transport);
        $mergedTransports = $this->transport->merge($updateTransports);

        return $mergedTransports->groupBy('name')->map(fn($group) => [
            'id' => $group->first()->type,
            'name' => $group->first()->name,
            'count' => $group->sum('count'),
        ])->values()->toArray();
    }
}
