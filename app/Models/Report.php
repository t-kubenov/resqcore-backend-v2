<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Update;
use App\Models\Facility;
use App\Models\BranchOffice;
use App\Models\User;
use App\Models\Transport;


class Report extends BaseOperation
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
        'branch_office_id',
        'facility_id',
        'reported_by',
        'report_code',
        'caller',
        'line_facility_location',
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

    private static $emergencyTypes = [
        self::EMERGENCY_TYPE_FIRE => 'Пожар',
        self::EMERGENCY_TYPE_INJURY => 'Травма',
        self::EMERGENCY_TYPE_BLACKOUT => 'Отключение электроэнергии',
        self::EMERGENCY_TYPE_COLLAPSE => 'Обрушение',
        self::EMERGENCY_TYPE_ACCIDENT => 'Несчастный случай',
        self::EMERGENCY_TYPE_STUCK => 'Застревание',
        self::EMERGENCY_TYPE_DISEASE => 'Заболевание',
        self::EMERGENCY_TYPE_FLOOD => 'Затопление',
        self::EMERGENCY_TYPE_TRAFFIC_ACCIDENT => 'ДТП',
        self::EMERGENCY_TYPE_GAS => 'Загазованность',
        self::EMERGENCY_TYPE_EXPLOSION => 'Взрыв',
        self::EMERGENCY_TYPE_PROVISION => 'Обеспечение',
        self::EMERGENCY_TYPE_PEOPLE_SEARCH => 'Поиск людей',
        self::EMERGENCY_TYPE_OPEN_FOUNTAIN => 'Открытый фонтан',
        self::EMERGENCY_TYPE_SPILL => 'Розлив',
        self::EMERGENCY_TYPE_OTHER => 'Иное',
    ];

    public static function getEmergencyTypes()
    {
        return self::$emergencyTypes;
    }

    public function getEmergencyNameAttribute()
    {
        return self::$emergencyTypes[$this->emergency_type] ?? null;
    }

    public function updates()
    {
        return $this->hasMany(Update::class, 'report_id');
    }

    public function facility()
    {
        return $this->belongsTo(Facility::class);
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
