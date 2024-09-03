<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Operation;
use App\Models\Update;


class Transport extends Model
{
    use HasFactory;

    protected $fillable = [
        'operation_id',
        'operation_update_id',
        'type',
        'count',
    ];

    const TRANSPORT_TYPE_AC = 1;
    const TRANSPORT_TYPE_OPERATIONAL_VEHICLE = 2;
    const TRANSPORT_TYPE_CAR_LADDER = 3;
    const TRANSPORT_TYPE_RPG_CAR = 4;
    const TRANSPORT_TYPE_SHIFT_CAR = 5;
    const TRANSPORT_TYPE_LIGHT_CAR = 6;
    const TRANSPORT_TYPE_LIGHT_CAR_HIGH_CROSS_COUNTY = 7;
    const TRANSPORT_TYPE_ASA_CAR = 8;

    private static $transportTypes = [
        self::TRANSPORT_TYPE_AC => 'АЦ',
        self::TRANSPORT_TYPE_OPERATIONAL_VEHICLE => 'Оперативный а/м',
        self::TRANSPORT_TYPE_CAR_LADDER => 'Автолестница',
        self::TRANSPORT_TYPE_RPG_CAR => 'Автомобиль РПГ',
        self::TRANSPORT_TYPE_SHIFT_CAR => 'Вахтовка',
        self::TRANSPORT_TYPE_LIGHT_CAR => 'Легковой а/м',
        self::TRANSPORT_TYPE_LIGHT_CAR_HIGH_CROSS_COUNTY => 'Легковой а/м повышенной проходимости',
        self::TRANSPORT_TYPE_ASA_CAR => 'а/м АСА',
    ];

    public static function getTransportTypes()
    {
        return self::$transportTypes;
    }

    public function getTransportTypeAttribute($value)
    {
        return self::$transportTypes[$value]?? null;
    }

    public function operation()
    {
        return $this->belongsTo(Operation::class);
    }

    public function operation_update()
    {
        return $this->belongsTo(Update::class);
    }
}
