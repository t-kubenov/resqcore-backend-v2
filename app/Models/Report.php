<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

//use Illuminate\Database\Eloquent\Model;

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
            'branch_office_id' => [
                'key' => 'data.attributes.branch_office_id',
                'name' => __('reports.fields.branch_office_id.name'),
                'alias' => __('reports.fields.branch_office_id.alias'),
                'type' => 'number',
                'rules' => ['sometimes', 'integer', 'exists:branch_offices,id'],
            ],
            'caller' => [
                'key' => 'data.attributes.caller',
                'name' => __('reports.fields.caller.name'),
                'alias' => __('reports.fields.caller.alias'),
                'type' => 'text',
                'rules' => ['required'],
            ],
            'call_date' => [
                'key' => 'data.attributes.call_date',
                'name' => __('reports.fields.call_date.name'),
                'alias' => __('reports.fields.call_date.alias'),
                'type' => 'datetime',
                'rules' => [
                    'required',
                    'date',
                    'before_or_equal:data.attributes.work_start_date',
                    'before_or_equal:tomorrow',
                ],
            ],
            'work_start_date' => [
                'key' => 'data.attributes.work_start_date',
                'name' => __('reports.fields.work_start_date.name'),
                'alias' => __('reports.fields.work_start_date.alias'),
                'type' => 'datetime',
                'rules' => [
                    'required',
                    'date',
                    'after_or_equal:data.attributes.call_date',
                    'before_or_equal:tomorrow',
                ],
            ],
            'emergency_type_id' => [
                'key' => 'data.attributes.emergency_type.id',
                'name' => __('reports.fields.emergency_type.name'),
                'alias' => __('reports.fields.emergency_type.alias'),
                'type' => 'dropdown',
                'rules' => ['required', 'integer', 'exists:emergency_types,id'],
                'options' => route('auxiliary.emergency-types'),
            ],
            'is_emergency_line' => [
                'key' => 'data.attributes.is_emergency_line',
                'name' => __('reports.fields.is_emergency_line.name'),
                'alias' => __('reports.fields.is_emergency_line.alias'),
                'type' => 'dropdown',
                'rules' => ['required', 'boolean'],
                'options' => route('auxiliary.departure-types'),
            ],
            'line_facility_owner' => [
                'key' => 'data.attributes.line_facility_owner',
                'name' => __('reports.fields.line_facility_owner.name'),
                'alias' => __('reports.fields.line_facility_owner.alias'),
                'type' => 'text',
                'rules' => ['required', 'string'],
            ],
            'emergency_location' => [
                'key' => 'data.attributes.emergency_location',
                'name' => __('reports.fields.emergency_location.name'),
                'alias' => __('reports.fields.emergency_location.alias'),
                'type' => 'text',
                'rules' => ['required', 'string'],
            ],
            'emergency_info' => [
                'key' => 'data.attributes.emergency_info',
                'name' => __('reports.fields.emergency_info.name'),
                'alias' => __('reports.fields.emergency_info.alias'),
                'type' => 'textarea',
                'rules' => ['nullable', 'string'],
            ],
            'transport' => [
                'key' => 'data.attributes.transport',
                'name' => __('reports.fields.transport.name'),
                'alias' => __('reports.fields.transport.alias'),
                'type' => 'multiselect',
                'rules' => ['required', 'array', 'min:1'],
            ],
        ], parent::fieldDefinitions());

    }

    public static function filters(): array
    {
        return [
            [
                'key' => 'emergency_type',
                'name' => __('reports.fields.emergency_type.name'),
                'rules' => ['sometimes', 'array', 'min:1'],
                'type' => 'multiselect',
                'options' => route('auxiliary.emergency-types'),
            ],
            [
                'key' => 'branch_office_id',
                'name' => __('reports.fields.branch_office_id.name'),
                'rules' => ['sometimes', 'array', 'min:1'],
                'type' => 'multiselect',
                'options' => route('auxiliary.branch-offices'),
                'dont_display_to' => ['branch_office_employee'],
            ],
            [
                'key' => 'is_emergency_line',
                'name' => __('reports.fields.is_emergency_line.name'),
                'rules' => ['sometimes', 'boolean'],
                'type' => 'dropdown',
                'options' => route('auxiliary.departure-types'),
            ],
            [
                'key' => 'date_start',
                'name' => 'Начало периода',
                'rules' => ['sometimes', 'date'],
                'type' => 'date',
            ],
            [
                'key' => 'date_end',
                'name' => 'Конец периода',
                'rules' => ['sometimes', 'date'],
                'type' => 'date',
            ],
            [
                'key' => 'search',
                'name' => 'Поиск',
                'rules' => ['sometimes', 'string', 'max:255'],
                'type' => 'text',
            ],
            [
                'key' => 'is_closed',
                'name' => __('reports.fields.is_closed.name'),
                'rules' => ['sometimes', 'boolean'],
                'type' => 'dropdown',
                'options' => [
                    [
                        'id' => 'true',
                        'name' => 'Да',
                    ],
                    [
                        'id' => 'false',
                        'name' => 'Нет',
                    ]
                ]
            ],
        ];
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

    public static function departureTypes(): array
    {
        return [
            [
                'id' => 'true',
                'name' => __('reports.emergency-line.true'),
            ],
            [
                'id' => 'false',
                'name' => __('reports.emergency-line.false'),
            ]
        ];
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
