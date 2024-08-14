<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transport extends Model
{
    use HasFactory;

    /*
        <option value="АЦ">АЦ</option>
        <option value="Оперативный а/м">Оперативный а/м</option>
        <option value="Автолестница">Автолестница</option>
        <option value="Автомобиль РПГ">Автомобиль РПГ</option>
        <option value="Вахтовка">Вахтовка</option>
        <option value="Легковой а/м">Легковой а/м</option>
        <option value="Легковой а/м  повышенной проходимости">Легковой а/м  повышенной проходимости</option>
        <option value="а/м АСА">а/м АСА</option>
    */


    const TRANSPORT_TYPE_AC = 1;
    const TRANSPORT_TYPE_OPERATIONAL_VEHICLE = 2;
    const TRANSPORT_TYPE_CAR_LADDER = 3;
    const TRANSPORT_TYPE_RPG_CAR = 4;
    const TRANSPORT_TYPE_SHIFT_CAR = 5;
    const TRANSPORT_TYPE_LIGHT_CAR = 6;
    const TRANSPORT_TYPE_LIGHT_CAR_HIGH_CROSS_COUNTY = 7;
    const TRANSPORT_TYPE_ASA_CAR = 8;
}
