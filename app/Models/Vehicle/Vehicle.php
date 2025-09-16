<?php

namespace App\Models\Vehicle;

use App\Enums\Vehicle\ActionType;
use App\Enums\Vehicle\TechnicalCondition;
use App\Enums\Vehicle\UsageType;
use App\Models\BranchOffice;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vehicle extends Model
{
    /** @use HasFactory<\Database\Factories\Vehicle\VehicleFactory> */
    use HasFactory;

    protected $fillable = [
        'branch_office_id',            // Наименование филиалов
        'name',                        // Марка автомашины
        'vehicle_type_id',             // Тип
        'plate_number',                // Гос. номер
        'year',                        // Год выпуска
        'location',                    // Место дислокации / обслуживаемый объект
        'vehicle_owner_id',            // Владелец
        'usage_type',                  // Вид пользования
        'vehicle_user_id',             // Организация-эксплуатант
        'rental_contract',             // Договор аренды
        'rental_price',                // Стоимость в месяц
        'technical_condition',         // Тех. состояние
        'action',                     // Действия
        'notes',                       // Уточнения
        'insurance_expiration_date',   // Дата окончания страхового полиса
        'inspection_expiration_date',  // Дата окончания тех. осмотра
        'inspection_date',             // Дата прохождения тех. осмотра
    ];

    protected $casts = [
        'usage_type' => UsageType::class,
        'action' => ActionType::class,
        'technical_condition' => TechnicalCondition::class,
        'insurance_expiration_date' => 'date',
        'inspection_expiration_date' => 'date',
        'inspection_date' => 'date',
        'rental_price' => 'decimal:2',
    ];

    public function branch_office(): BelongsTo
    {
        return $this->belongsTo(BranchOffice::class);
    }

    public function vehicle_type(): BelongsTo
    {
        return $this->belongsTo(VehicleType::class);
    }

    public function vehicle_owner(): BelongsTo
    {
        return $this->belongsTo(VehicleCompany::class);
    }

    public function vehicle_user(): BelongsTo
    {
        return $this->belongsTo(VehicleCompany::class);
    }

}
