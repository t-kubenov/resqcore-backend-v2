<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

//use Illuminate\Database\Eloquent\Model;

class Report extends BaseOperation
{
    use HasFactory;

    protected $fillable = [
        'branch_office_id',
        'facility_owner_id',
        'reported_by',
        'report_code',
        'caller',

        'line_facility_owner',
        'emergency_location',
        'emergency_info',
        'call_date',
        'work_start_date',
        'emergency_type',
        'minor_injury_count',
        'severe_injury_count',
        'fatal_injury_count',
        'evacuated_people_count',
        'provided_medical_count',
        'client_equipment_count',
        'third_parties_count',

        'minor_injury_total',
        'severe_injury_total',
        'fatal_injury_total',
        'evacuated_people_total',
        'provided_medical_total',
        'client_equipment_total',
        'third_parties_total',

        'branch_worker_count',
        'worker_man_hours',
        'work_scope',
        'situation_during_filing',
        'other_info',
        'is_emergency_line',
        'close_date',
        'is_closed',

        'last_update_created_at'
    ];

    public static function fieldDefinitions(): array
    {
        return array_merge([
            'branch_office_id' => [
                'key' => 'data.attributes.branch_office_id',
                'name' => trans('reports.fields.branch_office_id.name'),
                'alias' => trans('reports.fields.branch_office_id.alias'),
                'type' => 'integer',
                'required' => false,
                'rules' => ['sometimes', 'integer', 'exists:branch_offices,id'],
            ],
            'caller' => [
                'key' => 'data.attributes.caller',
                'name' => trans('reports.fields.caller.name'),
                'alias' => trans('reports.fields.caller.alias'),
                'type' => 'string',
                'required' => true,
                'rules' => ['required'],
            ],
            'call_date' => [
                'key' => 'data.attributes.call_date',
                'name' => trans('reports.fields.call_date.name'),
                'alias' => trans('reports.fields.call_date.alias'),
                'type' => 'date',
                'required' => true,
                'rules' => [
                    'required',
                    'date',
                    'before_or_equal:data.attributes.work_start_date',
                    'before_or_equal:tomorrow',
                ],
            ],
            'work_start_date' => [
                'key' => 'data.attributes.work_start_date',
                'name' => trans('reports.fields.work_start_date.name'),
                'alias' => trans('reports.fields.work_start_date.alias'),
                'type' => 'date',
                'required' => true,
                'rules' => [
                    'required',
                    'date',
                    'after_or_equal:data.attributes.call_date',
                    'before_or_equal:tomorrow',
                ],
            ],
            'emergency_type_id' => [
                'key' => 'data.attributes.emergency_type.id',
                'name' => trans('reports.fields.emergency_type.name'),
                'alias' => trans('reports.fields.emergency_type.alias'),
                'type' => 'integer',
                'required' => true,
                'rules' => ['required', 'integer', 'exists:emergency_types,id'],
            ],
            'work_scope' => [
                'key' => 'data.attributes.work_scope',
                'name' => trans('reports.fields.work_scope.name'),
                'alias' => trans('reports.fields.work_scope.alias'),
                'type' => 'string',
                'required' => true,
                'rules' => ['required'],
            ],
            'is_emergency_line' => [
                'key' => 'data.attributes.is_emergency_line',
                'name' => trans('reports.fields.is_emergency_line.name'),
                'alias' => trans('reports.fields.is_emergency_line.alias'),
                'type' => 'boolean',
                'required' => true,
                'rules' => ['required', 'boolean'],
            ],
            'line_facility_owner' => [
                'key' => 'data.attributes.line_facility_owner',
                'name' => trans('reports.fields.line_facility_owner.name'),
                'alias' => trans('reports.fields.line_facility_owner.alias'),
                'type' => 'string',
                'required' => true,
                'rules' => ['required', 'string'],
            ],
            'emergency_location' => [
                'key' => 'data.attributes.emergency_location',
                'name' => trans('reports.fields.emergency_location.name'),
                'alias' => trans('reports.fields.emergency_location.alias'),
                'type' => 'string',
                'required' => true,
                'rules' => ['required', 'string'],
            ],
            'emergency_info' => [
                'key' => 'data.attributes.emergency_info',
                'name' => trans('reports.fields.emergency_info.name'),
                'alias' => trans('reports.fields.emergency_info.alias'),
                'type' => 'string',
                'required' => false,
                'rules' => ['nullable', 'string'],
            ],
        ], parent::fieldDefinitions());

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

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    public function transport()
    {
        return $this->hasMany(Transport::class);
    }

    public function groupedTransport(): Attribute
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
