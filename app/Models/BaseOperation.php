<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaseOperation extends Model
{
    use HasFactory;

    // this is the base operation model, it should not be used directly
    // it contains the fields that are stored in operation updates, but not in the main operation


    protected $fillable = [
        'minor_injury_count',
        'severe_injury_count',
        'fatal_injury_count',
        'evacuated_people_count', // ex people_evacuated
        'provided_medical_count',
        'work_scope', // ex scope_work
        'situation_during_filing', // ex situation_filing
        'client_equipment_count', // ex customer_technology
        'branch_worker_count', // ex branch_workers varchar
        'worker_man_hours', // used to be merged with branch worker count
        'third_parties_count', // ex party_authority other organizations involved
        'close_date', // ex finish_work_date
    ];

    /*  version from query
        'branch_id',
        'accident_id',
        'reporting_id',
        'who_call',
        'location_accident',
        'call_date',
        'arrival_date',
        'emergency_type',
        'object_owner',
        'branch_workers',
        'object_data',
        'minor_injury',
        'severe_injury',
        'fatal_injury',
        'people_evacuated',
        'provided_medical',
        'scope_work',
        'situation_filing',
        'customer_technology',
        'party_authority',
        'other_data',
        'line_emergency',
    */
}
