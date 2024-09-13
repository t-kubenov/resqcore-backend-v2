<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'is_closed'
    ];

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

    // public function facility()
    // {
    //     return $this->belongsTo(Facility::class);
    // }

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
}
