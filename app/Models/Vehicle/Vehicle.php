<?php

namespace App\Models\Vehicle;

use App\Enums\FieldParameter;
use App\Enums\FieldType;
use App\Enums\Vehicle\ActionType;
use App\Enums\Vehicle\DriveType;
use App\Enums\Vehicle\FuelType;
use App\Enums\Vehicle\GearboxType;
use App\Enums\Vehicle\TechnicalCondition;
use App\Enums\Vehicle\UsageType;
use App\Models\BranchOffice;
use App\Models\RescueCompany;
use App\Observers\Vehicle\VehicleObserver;
use App\Traits\FormatsColumns;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

#[ObservedBy(VehicleObserver::class)]
class Vehicle extends Model
{
    use HasFactory, formatsColumns;

    protected $fillable = [
        'branch_office_id',            // Наименование филиалов
        'brand',                       // Марка автомашины
        'vehicle_type_id',             // Тип
        'plate_number',                // Гос. номер
        'image_path',
        'year',                        // Год выпуска
        'engine_size',
        'fuel_type',
        'gearbox',
        'drive',
        'location',                    // Место дислокации / обслуживаемый объект
        'vehicle_owner_id',            // Владелец
        'usage_type',                  // Вид пользования
        'vehicle_user_id',             // Организация-эксплуатант
        'rental_contract',             // Договор аренды
        'rental_price',                // Стоимость в месяц
        'technical_condition',         // Тех. состояние
        'action',                      // Действия
        'notes',                       // Уточнения
        'insurance_expiration_date',   // Дата окончания страхового полиса
        'inspection_expiration_date',  // Дата окончания тех. осмотра
        'inspection_date',             // Дата прохождения тех. осмотра
    ];

    protected $casts = [
        'usage_type' => UsageType::class,
        'action' => ActionType::class,
        'technical_condition' => TechnicalCondition::class,
        'fuel_type' => FuelType::class,
        'gearbox' => GearboxType::class,
        'drive' => DriveType::class,
        'insurance_expiration_date' => 'date',
        'inspection_expiration_date' => 'date',
        'inspection_date' => 'date',
        'rental_price' => 'decimal:2',
    ];

    public static function fieldDefinitions(): array
    {
        return [
            'id' => [
                'key' => 'data.attributes.id',
                'name' => 'id',
                'label' => __('vehicles.fields.id'),
                'type' => FieldType::String,
                'parameters' => [
                    FieldParameter::IsHidden,
                    FieldParameter::IsFilterable,
                    FieldParameter::IsFilterableForCA,
                    FieldParameter::ShowInTable,
                    FieldParameter::ShowInCard,
                ],
            ],
            'branch_office_id' => [
                'key' => 'data.attributes.branch_office_id',
                'name' => 'branch_office_id',
                'label' => __('vehicles.fields.branch_office_id'),
                'type' => FieldType::SingleChoiceEnum,
                'options' => route('auxiliary.field-options', 'branch_office'),
                'parameters' => [
                    FieldParameter::IsFilterableForCA,
                    FieldParameter::ShowInTable,
                    FieldParameter::ShowInCard,
                ],
            ],
            'brand' => [
                'key' => 'data.attributes.brand',
                'name' => 'brand',
                'label' => __('vehicles.fields.brand'),
                'type' => FieldType::String,
                'rules' => [
                    'max:255',
                ],
                'required' => true,
                'parameters' => [
                    FieldParameter::ShowInTable,
                    FieldParameter::ShowInCard,
                ],
            ],
            'vehicle_type_id' => [
                'key' => 'data.attributes.vehicle_type_id',
                'name' => 'vehicle_type_id',
                'label' => __('vehicles.fields.vehicle_type_id'),
                'type' => FieldType::SingleChoiceEnum,
                'required' => true,
                'options' => route('auxiliary.field-options', 'vehicle_type_id'),
                'parameters' => [
                    FieldParameter::IsFilterableForCA,
                    FieldParameter::IsFilterable,
                    FieldParameter::ShowInTable,
                    FieldParameter::ShowInCard,
                ],
            ],
            'plate_number' => [
                'key' => 'data.attributes.plate_number',
                'name' => 'plate_number',
                'label' => __('vehicles.fields.plate_number'),
                'type' => FieldType::String,
                'rules' => [
                    'max:255',
                    'unique:vehicles,plate_number'
                ],
                'required' => true,
                'parameters' => [
                    FieldParameter::ShowInTable,
                    FieldParameter::ShowInCard,
                ],
            ],
            'year' => [
                'key' => 'data.attributes.year',
                'name' => 'year',
                'label' => __('vehicles.fields.year'),
                'type' => FieldType::Int,
                'rules' => [
                    'required',
                    'integer',
                    'digits:4',
                    'min:1900',
                    'max:' . date('Y'),
                ],
                'required' => true,
                'parameters' => [
                    FieldParameter::ShowInCard,
                ],
            ],
            'engine_size' => [
                'key' => 'data.attributes.engine_size',
                'name' => 'engine_size',
                'label' => __('vehicles.fields.engine_size'),
                'type' => FieldType::Float,
                'rules' => [
                    'min: 0.5',
                    'max: 50.0',
                ],
                'parameters' => [
                    FieldParameter::ShowInCard,
                ],
            ],
            'fuel_type' => [
                'key' => 'data.attributes.fuel_type',
                'name' => 'fuel_type',
                'label' => __('vehicles.fields.fuel_type'),
                'type' => FieldType::SingleChoiceEnum,
                'options' => route('auxiliary.field-options', 'fuel_type'),
                'parameters' => [
                    FieldParameter::ShowInCard,
                ],
            ],
            'gearbox' => [
                'key' => 'data.attributes.gearbox',
                'name' => 'gearbox',
                'label' => __('vehicles.fields.gearbox'),
                'type' => FieldType::SingleChoiceEnum,
                'options' => route('auxiliary.field-options', 'gearbox'),
                'parameters' => [
                    FieldParameter::ShowInCard,
                ],
            ],
            'drive' => [
                'key' => 'data.attributes.drive',
                'name' => 'drive',
                'label' => __('vehicles.fields.drive'),
                'type' => FieldType::SingleChoiceEnum,
                'options' => route('auxiliary.field-options', 'drive'),
                'parameters' => [
                    FieldParameter::ShowInCard,
                ],
            ],
            'location' => [
                'key' => 'data.attributes.location',
                'name' => 'location',
                'label' => __('vehicles.fields.location'),
                'type' => FieldType::String,
                'rules' => [
                    'max:255',
                ],
                'required' => true,
                'parameters' => [
                    FieldParameter::ShowInCard,
                ],
            ],
            'vehicle_owner_id' => [
                'key' => 'data.attributes.vehicle_owner_id',
                'name' => 'vehicle_owner_id',
                'label' => __('vehicles.fields.vehicle_owner_id'),
                'type' => FieldType::SingleChoiceEnum,
                'required' => true,
                'options' => route('auxiliary.field-options', 'vehicle_companies'),
                'parameters' => [
                    FieldParameter::ShowInTable,
                    FieldParameter::ShowInCard,
                ],
            ],

            'usage_type' => [
                'key' => 'data.attributes.usage_type',
                'name' => 'usage_type',
                'label' => __('vehicles.fields.usage_type'),
                'type' => FieldType::SingleChoiceEnum,
                'required' => true,
                'options' => route('auxiliary.field-options', 'usage_type'),
                'parameters' => [
                    FieldParameter::IsFilterableForCA,
                    FieldParameter::IsFilterable,
                    FieldParameter::ShowInTable,
                    FieldParameter::ShowInCard,
                ],
            ],

            'vehicle_user_id' => [
                'key' => 'data.attributes.vehicle_user_id',
                'name' => 'vehicle_user_id',
                'label' => __('vehicles.fields.vehicle_user_id'),
                'type' => FieldType::SingleChoiceEnum,
                'options' => route('auxiliary.field-options', 'vehicle_companies'),
                'parameters' => [
                    FieldParameter::ShowInTable,
                    FieldParameter::ShowInCard,
                ],
            ],

            'rental_contract' => [
                'key' => 'data.attributes.rental_contract',
                'name' => 'rental_contract',
                'label' => __('vehicles.fields.rental_contract'),
                'type' => FieldType::String,
                'rules' => [
                    'max:255',
                ],
                'parameters' => [
                    FieldParameter::ShowInCard,
                ],
            ],

            'rental_price' => [
                'key' => 'data.attributes.rental_price',
                'name' => 'rental_price',
                'label' => __('vehicles.fields.rental_price'),
                'type' => FieldType::Float,
                'rules' => [
                    'numeric',
                    'min:0',
                ],
                'parameters' => [
                    FieldParameter::ShowInCard,
                ],
            ],

            'technical_condition' => [
                'key' => 'data.attributes.technical_condition',
                'name' => 'technical_condition',
                'label' => __('vehicles.fields.technical_condition'),
                'type' => FieldType::SingleChoiceEnum,
                'required' => true,
                'options' => route('auxiliary.field-options', 'technical_condition'),
                'parameters' => [
                    FieldParameter::IsFilterableForCA,
                    FieldParameter::IsFilterable,
                    FieldParameter::ShowInTable,
                    FieldParameter::ShowInCard,
                ],
            ],

            'action' => [
                'key' => 'data.attributes.action',
                'name' => 'action',
                'label' => __('vehicles.fields.action'),
                'type' => FieldType::SingleChoiceEnum,
                'options' => route('auxiliary.field-options', 'action_type'),
                'parameters' => [
                    FieldParameter::IsFilterableForCA,
                    FieldParameter::IsFilterable,
                    FieldParameter::ShowInTable,
                    FieldParameter::ShowInCard,
                ],
            ],

            'notes' => [
                'key' => 'data.attributes.notes',
                'name' => 'notes',
                'label' => __('vehicles.fields.notes'),
                'type' => FieldType::Text,
                'parameters' => [
                    FieldParameter::ShowInCard,
                ],
            ],

            'insurance_expiration_date' => [
                'key' => 'data.attributes.insurance_expiration_date',
                'name' => 'insurance_expiration_date',
                'label' => __('vehicles.fields.insurance_expiration_date'),
                'type' => FieldType::Date,
                'parameters' => [
                    FieldParameter::ShowInCard,
                ],
            ],

            'inspection_expiration_date' => [
                'key' => 'data.attributes.inspection_expiration_date',
                'name' => 'inspection_expiration_date',
                'label' => __('vehicles.fields.inspection_expiration_date'),
                'type' => FieldType::Date,
                'parameters' => [
                    FieldParameter::ShowInCard,
                ],
            ],

            'inspection_date' => [
                'key' => 'data.attributes.inspection_date',
                'name' => 'inspection_date',
                'label' => __('vehicles.fields.inspection_date'),
                'type' => FieldType::Date,
                'parameters' => [
                    FieldParameter::ShowInCard,
                ],
            ],

            'image_path' => [
                'key' => 'data.attributes.image_path',
                'name' => 'image_path',
                'label' => __('vehicles.fields.image'),
                'type' => FieldType::String,
                'parameters' => [
                    FieldParameter::ShowInCard,
                ],
            ],
        ];
    }

    public function getReadableData(): array
    {
        return [
            'id' => $this->id,
            'branch_office_id' => $this->branch_office?->name,
            'brand' => $this->brand,
            'vehicle_type_id' => $this->vehicle_type?->name,
            'plate_number' => $this->plate_number,
            'image_path' => $this->image_path? Storage::disk()->url($this->image_path): null,
            'year' => $this->year,
            'fuel_type' => $this->fuel_type? __('etc.enum_options.' . $this->fuel_type->name): null,
            'gearbox' => $this->gearbox? __('etc.enum_options.' . $this->gearbox->name): null,
            'drive' => $this->drive? __('etc.enum_options.' . $this->drive->name): null,
            'location' => $this->location,
            'vehicle_owner_id' => $this->vehicle_owner?->name,
            'usage_type' => $this->usage_type? __('etc.enum_options.' . $this->usage_type->name): null,
            'vehicle_user_id' => $this->vehicle_user?->name,
            'rental_contract' => $this->rental_contract,
            'rental_price' => $this->rental_price,
            'technical_condition' => $this->technical_condition? __('etc.enum_options.' . $this->technical_condition->name): null,
            'action' => $this->action? __('etc.enum_options.' . $this->action->name): null,
            'notes' => $this->notes,
            'insurance_expiration_date' => $this->insurance_expiration_date
                ? Carbon::parse($this->insurance_expiration_date)->format('d.m.Y')
                : null,
            'inspection_expiration_date' => $this->inspection_expiration_date
                ? Carbon::parse($this->inspection_expiration_date)->format('d.m.Y')
                : null,
            'inspection_date' => $this->inspection_date
                ? Carbon::parse($this->inspection_date)->format('d.m.Y')
                : null,
        ];
    }

    public function branch_office(): BelongsTo
    {
        return $this->belongsTo(BranchOffice::class);
    }

    public function rescue_company(): BelongsTo
    {
        return $this->branch_office->rescue_company();
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
