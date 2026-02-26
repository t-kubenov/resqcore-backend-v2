<?php

namespace App\Report;

use App\Document\Http\GenericResource;

class ReportResource extends GenericResource
{
    protected ?array $columnOrder = [
        'branch_office',
        'reported_by',
        'report_code',
        'caller',
        'facility_owner',
        'emergency_location',
        'emergency_info',
        'call_date',
        'work_start_date',
        'emergency_type',
        'minor_injury_total',
        'severe_injury_total',
        'fatal_injury_total',
        'evacuated_people_total',
        'provided_medical_total',
        'client_equipment_total',
        'third_parties_total',
        'branch_worker_total',
        'other_info',
        'is_emergency_line',
        'last_update_created_at',
        'worker_man_hours',
        'close_date',
        'is_closed',
    ];

    protected function formatColumns()
    {
        return $this::resolveColumns($this->columnOrder);
    }
}
