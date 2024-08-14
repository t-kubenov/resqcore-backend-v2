<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operation extends BaseOperation
{
    use HasFactory;


    const EMERGENCY_TYPE_FIRE = 1;
    const EMERGENCY_TYPE_INJURY = 2;
    const EMERGENCY_TYPE_BLACKOUT = 3;
    const EMERGENCY_TYPE_COLLAPSE = 4;
    const EMERGENCY_TYPE_ACCIDENT = 5;
    const EMERGENCY_TYPE_STUCK = 6;
    const EMERGENCY_TYPE_DISEASE = 7;
    const EMERGENCY_TYPE_FLOOD = 8;
    const EMERGENCY_TYPE_TRAFFIC_ACCIDENT = 9;
    const EMERGENCY_TYPE_GAS = 10;
    const EMERGENCY_TYPE_EXPLOSION = 11;
    const EMERGENCY_TYPE_PROVISION = 12;
    const EMERGENCY_TYPE_PEOPLE_SEARCH = 13;
    const EMERGENCY_TYPE_OPEN_FOUNTAIN = 14;
    const EMERGENCY_TYPE_SPILL = 15;
    const EMERGENCY_TYPE_OTHER = 16;


    protected $fillable = [
        'branch_id', // ex branch
        'object_id', // if it's on the emergency line
        'reported_by', // ex reporting_id
        'report_code', // code of the report , i.e. 10-4
        'caller', // ex who_call
        'accident_location', // ex location_accident; if the object is not from the emergency line,..
        'emergency_info', // ex object_data Данные по аварии
        'call_date',
        'work_start_date',
        'emergency_type', // enum basically
        'is_emergency_line', // ex line_emergency
        'close_date', // ex finish_work_date
        'is_closed', // ex status
    ];


    public function updates()
    {
        return $this->hasMany(OperationUpdate::class);
    }
}
