<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

abstract class BaseOperation extends Model
{
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
}
