<?php

namespace App\Models\Report;

use App\Enums\FieldParameter;
use App\Enums\FieldType;
use App\Models\BranchOffice;
use App\Models\Company;
use App\Models\EmergencyType;
use App\Models\RescueCompany;
use App\Traits\FormatsColumns;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Validation\Rule;


class Report extends ReportBase
{
    use HasFactory, FormatsColumns;

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
        return array_replace_recursive(parent::fieldDefinitions(), [
            'branch_office' => [
                'key' => 'data.attributes.branch_office_id',
                'name' => 'branch_office',
                'label' => __('reports.fields.branch_office_id.name'),
                'alias' => __('reports.fields.branch_office_id.alias'),
                'type' => FieldType::SingleChoiceEnum,
                'rules' => ['exists:branch_offices,id'],
                'required' => false,
                'options' => route('auxiliary.field-options', 'branch_office'),
                'parameters' => [
                    FieldParameter::IsFilterableForCA,
                    FieldParameter::IsHidden
                ]
            ],
            'reported_by' => [
                'key' => 'data.attributes.reported_by',
                'name' => 'reported_by',
                'label' => __('reports.fields.reported_by.name'),
                'alias' => __('reports.fields.reported_by.alias'),
                'type' => FieldType::String,
                'parameters' => [
                    FieldParameter::IsHidden,
                ]
            ],
            'facility_owner' => [
                'key' => 'data.attributes.facility_owner_id',
                'name' => 'facility_owner',
                'label' => __('reports.fields.facility_owner.name'),
                'alias' => __('reports.fields.facility_owner.alias'),
                'type' => FieldType::SingleChoiceEnum,
                'rules' => ['exists:companies,id'],
                'required' => false,
                'parameters' => [
                    FieldParameter::IsHidden,
                    FieldParameter::ShowInTable,
                    FieldParameter::TableOrder->value => 0,
                ]
            ],
            'caller' => [
                'key' => 'data.attributes.caller',
                'name' => 'caller',
                'label' => __('reports.fields.caller.name'),
                'alias' => __('reports.fields.caller.alias'),
                'type' => FieldType::String,
                'required' => true,
                'rules' => ['max:255'],
                'parameters' => [
                    FieldParameter::CategoryId->value => 1,
                ]
            ],
            'call_date' => [
                'key' => 'data.attributes.call_date',
                'name' => 'call_date',
                'label' => __('reports.fields.call_date.name'),
                'alias' => __('reports.fields.call_date.alias'),
                'type' => FieldType::Datetime,
                'required' => true,
                'rules' => [
                    'before_or_equal:data.attributes.work_start_date',
                    'before_or_equal:tomorrow',
                ],
                'parameters' => [
                    FieldParameter::IsFilterable,
                    FieldParameter::IsFilterableForCA,
                    FieldParameter::CategoryId->value => 1,
                ]
            ],
            'work_start_date' => [
                'key' => 'data.attributes.work_start_date',
                'name' => 'work_start_date',
                'label' => __('reports.fields.work_start_date.name'),
                'alias' => __('reports.fields.work_start_date.alias'),
                'type' => FieldType::Datetime,
                'required' => true,
                'rules' => [
                    'after_or_equal:data.attributes.call_date',
                    'before_or_equal:tomorrow',
                ],
                'parameters' => [
                    FieldParameter::CategoryId->value => 1,
                ]
            ],
            'emergency_type' => [
                'key' => 'data.attributes.emergency_type',
                'name' => 'emergency_type',
                'label' => __('reports.fields.emergency_type.name'),
                'alias' => __('reports.fields.emergency_type.alias'),
                'type' => FieldType::SingleChoiceEnum,
                'required' => true,
                'rules' => [
                    Rule::exists('emergency_types', 'id')
//                          ->where(function ($query) {
//                        $query->where('rescue_company_id', auth()->user()->rescue_company->id);
//                        dd(auth()->user()->rescue_company); // todo: this always returns 1. wtf?
//                    })
                ],
                'options' => route('auxiliary.field-options', 'emergency_type'),
                'parameters' => [
                    FieldParameter::IsFilterable,
                    FieldParameter::IsFilterableForCA,
                    FieldParameter::CategoryId->value => 1,
                    FieldParameter::ShowInTable,
                    FieldParameter::TableOrder->value => 1,
                ]
            ],
            'is_emergency_line' => [
                'key' => 'data.attributes.is_emergency_line',
                'name' => 'is_emergency_line',
                'label' => __('reports.fields.is_emergency_line.name'),
                'alias' => __('reports.fields.is_emergency_line.alias'),
                'type' => FieldType::Boolean,
                'required' => true,
                'options' => route('auxiliary.field-options', 'departure_type'),
                'parameters' => [
                    FieldParameter::IsFilterable,
                    FieldParameter::IsFilterableForCA,
                    FieldParameter::CategoryId->value => 1,
                ]
            ],
            'line_facility_owner' => [
                'key' => 'data.attributes.line_facility_owner',
                'name' => 'line_facility_owner',
                'label' => __('reports.fields.line_facility_owner.name'),
                'alias' => __('reports.fields.line_facility_owner.alias'),
                'type' => FieldType::String,
                'required' => true,
                'rules' => ['required', 'string', 'max:255'],
                'parameters' => [
                    FieldParameter::CategoryId->value => 1,
                ]
            ],
            'emergency_location' => [
                'key' => 'data.attributes.emergency_location',
                'name' => 'emergency_location',
                'label' => __('reports.fields.emergency_location.name'),
                'alias' => __('reports.fields.emergency_location.alias'),
                'type' => FieldType::String,
                'required' => true,
                'rules' => ['required', 'string', 'max:255'],
                'parameters' => [
                    FieldParameter::CategoryId->value => 1,
                    FieldParameter::ShowInTable,
                    FieldParameter::TableOrder->value => 2,
                ]
            ],
            'emergency_info' => [
                'key' => 'data.attributes.emergency_info',
                'name' => 'emergency_info',
                'label' => __('reports.fields.emergency_info.name'),
                'alias' => __('reports.fields.emergency_info.alias'),
                'type' => FieldType::String,
                'rules' => ['nullable', 'string', 'max:255'],
                'parameters' => [
                    FieldParameter::CategoryId->value => 1,
                ]
            ],
            'transport' => [
                'required' => true,
            ],
            'is_closed' => [
                'key' => 'data.attributes.is_closed',
                'name' => 'is_closed',
                'label' => __('reports.fields.is_closed.name'),
                'alias' => __('reports.fields.is_closed.alias'),
                'type' => FieldType::Boolean,
                'options' => route('auxiliary.field-options', 'boolean'),
                'parameters' => [
                    FieldParameter::IsFilterable,
                    FieldParameter::IsHidden,
                    FieldParameter::IsFilterableForCA
                ]
            ],
            'report_code' => [
                'key' => 'data.attributes.report_code',
                'name' => 'report_code',
                'label' => __('reports.fields.report_code.name'),
                'alias' => __('reports.fields.report_code.alias'),
                'type' => FieldType::String,
                'rules' => ['max:255'],
                'parameters' => [
                    FieldParameter::IsHidden,
                ]
            ],
            'minor_injury_total' => [
                'key' => 'data.attributes.minor_injury_total',
                'name' => 'minor_injury_total',
                'label' => __('reports.fields.minor_injury_total.name'),
                'alias' => __('reports.fields.minor_injury_total.alias'),
                'type' => FieldType::Int,
                'parameters' => [
                    FieldParameter::IsHidden,
                    FieldParameter::IsTotalField,
                ]
            ],
            'severe_injury_total' => [
                'key' => 'data.attributes.severe_injury_total',
                'name' => 'severe_injury_total',
                'label' => __('reports.fields.severe_injury_total.name'),
                'alias' => __('reports.fields.severe_injury_total.alias'),
                'type' => FieldType::Int,
                'parameters' => [
                    FieldParameter::IsHidden,
                    FieldParameter::IsTotalField,
                ]
            ],
            'fatal_injury_total' => [
                'key' => 'data.attributes.fatal_injury_total',
                'name' => 'fatal_injury_total',
                'label' => __('reports.fields.fatal_injury_total.name'),
                'alias' => __('reports.fields.fatal_injury_total.alias'),
                'type' => FieldType::Int,
                'parameters' => [
                    FieldParameter::IsHidden,
                    FieldParameter::IsTotalField,
                ]
            ],
            'evacuated_people_total' => [
                'key' => 'data.attributes.evacuated_people_total',
                'name' => 'evacuated_people_total',
                'label' => __('reports.fields.evacuated_people_total.name'),
                'alias' => __('reports.fields.evacuated_people_total.alias'),
                'type' => FieldType::Int,
                'parameters' => [
                    FieldParameter::IsHidden,
                    FieldParameter::IsTotalField,
                ]
            ],
            'provided_medical_total' => [
                'key' => 'data.attributes.provided_medical_total',
                'name' => 'provided_medical_total',
                'label' => __('reports.fields.provided_medical_total.name'),
                'alias' => __('reports.fields.provided_medical_total.alias'),
                'type' => FieldType::Int,
                'parameters' => [
                    FieldParameter::IsHidden,
                    FieldParameter::IsTotalField,
                ]
            ],
            'client_equipment_total' => [
                'key' => 'data.attributes.client_equipment_total',
                'name' => 'client_equipment_total',
                'label' => __('reports.fields.client_equipment_total.name'),
                'alias' => __('reports.fields.client_equipment_total.alias'),
                'type' => FieldType::Int,
                'parameters' => [
                    FieldParameter::IsHidden,
                    FieldParameter::IsTotalField,
                ]
            ],
            'third_parties_total' => [
                'key' => 'data.attributes.third_parties_total',
                'name' => 'third_parties_total',
                'label' => __('reports.fields.third_parties_total.name'),
                'alias' => __('reports.fields.third_parties_total.alias'),
                'type' => FieldType::Int,
                'parameters' => [
                    FieldParameter::IsHidden,
                    FieldParameter::IsTotalField,
                ]
            ],
            'branch_worker_total' => [
                'key' => 'data.attributes.branch_worker_total',
                'name' => 'branch_worker_total',
                'label' => __('reports.fields.branch_worker_total.name'),
                'alias' => __('reports.fields.branch_worker_total.alias'),
                'type' => FieldType::Int,
                'parameters' => [
                    FieldParameter::IsHidden,
                    FieldParameter::IsTotalField,
                ]
            ],
            'other_info' => [
                'key' => 'data.attributes.other_info',
                'name' => 'other_info',
                'label' => __('reports.fields.other_info.name'),
                'alias' => __('reports.fields.other_info.alias'),
                'type' => FieldType::Text,
                'parameters' => [
                    FieldParameter::CategoryId->value => 2,
                ]
            ],
            'last_update_created_at' => [
                'key' => 'data.attributes.last_update_created_at',
                'name' => 'last_update_created_at',
                'label' => __('reports.fields.last_update_created_at.name'),
                'alias' => __('reports.fields.last_update_created_at.alias'),
                'type' => FieldType::Datetime,
                'parameters' => [
                    FieldParameter::IsHidden,
                ]
            ],
            'branch_worker_count' => [
                'required' => true,
                'rules' => [
                    'min:1',
                ],
            ]
        ]);

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
        return $this->hasOneThrough(RescueCompany::class, BranchOffice::class);
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

    public static function generateReportCode(int $branchOfficeId)
    {
        $reportCount = Report::where('branch_office_id', $branchOfficeId)->count() + 1;
        return $branchOfficeId . '-' . $reportCount;
    }

    public function calculateTotals(): void
    {
        $this->minor_injury_total = $this->minor_injury_count;
        $this->severe_injury_total = $this->severe_injury_count;
        $this->fatal_injury_total = $this->fatal_injury_count;
        $this->evacuated_people_total = $this->evacuated_people_count;
        $this->provided_medical_total = $this->provided_medical_count;
        $this->client_equipment_total = $this->client_equipment_count;
        $this->third_parties_total = $this->third_parties_count;
        $this->branch_worker_total = $this->branch_worker_count;

        $this->save();
    }

    public function close(): void
    {
        if ($this->close_date && $this->worker_man_hours > 0) {
            $this->is_closed = true;
            $this->save();
        }
    }

}
