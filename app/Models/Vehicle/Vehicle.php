<?php

namespace App\Models\Vehicle;

use App\Enums\CustomFieldParameter;
use App\Enums\CustomFieldType;
use App\Enums\Vehicle\ActionType;
use App\Enums\Vehicle\TechnicalCondition;
use App\Enums\Vehicle\UsageType;
use App\Models\BranchOffice;
use App\Traits\FormatsColumns;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vehicle extends Model
{
    use HasFactory, formatsColumns;

    protected $fillable = [
        'branch_office_id',            // Наименование филиалов
        'brand',                       // Марка автомашины
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

    public static function fieldDefinitions(): array
    {
        return [
            'branch_office_id' => [
                'key' => 'data.attributes.branch_office_id',
                'name' => 'branch_office_id',
                'label' => __('vehicles.fields.branch_office_id'),
                'type' => CustomFieldType::SingleChoiceEnum,
                'options' => route('auxiliary.field-options', 'branch_office'),
                'parameters' => [
                    CustomFieldParameter::IsHidden,
                    CustomFieldParameter::IsFilterableForCA,
                    CustomFieldParameter::ShowInTable,
                    CustomFieldParameter::ShowInCard,
                ],
            ],
            'brand' => [
                'key' => 'data.attributes.brand',
                'name' => 'brand',
                'label' => __('vehicles.fields.brand'),
                'type' => CustomFieldType::String,
                'rules' => [
                    'max:255',
                ],
                'required' => true,
                'parameters' => [
                    CustomFieldParameter::IsFilterableForCA,
                    CustomFieldParameter::IsFilterable,
                    CustomFieldParameter::ShowInTable,
                    CustomFieldParameter::ShowInCard,
                ],
            ],
            'vehicle_type_id' => [
                'key' => 'data.attributes.vehicle_type_id',
                'name' => 'vehicle_type_id',
                'label' => __('vehicles.fields.vehicle_type_id'),
                'type' => CustomFieldType::SingleChoiceEnum,
                'required' => true,
                'options' => route('auxiliary.field-options', 'vehicle_type_id'),
                'parameters' => [
                    CustomFieldParameter::IsFilterableForCA,
                    CustomFieldParameter::IsFilterable,
                    CustomFieldParameter::ShowInTable,
                    CustomFieldParameter::ShowInCard,
                ],
            ],
            'plate_number' => [
                'key' => 'data.attributes.plate_number',
                'name' => 'plate_number',
                'label' => __('vehicles.fields.plate_number'),
                'type' => CustomFieldType::String,
                'rules' => [
                    'max:255',
                ],
                'required' => true,
                'parameters' => [
                    CustomFieldParameter::IsFilterableForCA,
                    CustomFieldParameter::IsFilterable,
                    CustomFieldParameter::ShowInTable,
                    CustomFieldParameter::ShowInCard,
                ],
            ],
            'year' => [
                'key' => 'data.attributes.year',
                'name' => 'year',
                'label' => __('vehicles.fields.year'),
                'type' => CustomFieldType::String,
                'rules' => [
                    "required|digits:4|integer|min:1900|max:{{ date('Y') + 1 }}",
                ],
                'required' => true,
                'parameters' => [
                    CustomFieldParameter::IsFilterableForCA,
                    CustomFieldParameter::IsFilterable,
                    CustomFieldParameter::ShowInTable,
                    CustomFieldParameter::ShowInCard,
                ],
            ],
            'location' => [
                'key' => 'data.attributes.location',
                'name' => 'location',
                'label' => __('vehicles.fields.location'),
                'type' => CustomFieldType::Int,
                'rules' => [
                    'max:255',
                ],
                'required' => true,
                'parameters' => [
                    CustomFieldParameter::IsFilterableForCA,
                    CustomFieldParameter::IsFilterable,
                    CustomFieldParameter::ShowInTable,
                    CustomFieldParameter::ShowInCard,
                ],
            ],
            'vehicle_owner_id' => [
                'key' => 'data.attributes.vehicle_owner_id',
                'name' => 'vehicle_owner_id',
                'label' => __('vehicles.fields.vehicle_owner_id'),
                'type' => CustomFieldType::SingleChoiceEnum,
                'required' => true,
                'options' => route('auxiliary.field-options', 'vehicle_companies'),
                'parameters' => [
                    CustomFieldParameter::IsFilterableForCA,
                    CustomFieldParameter::IsFilterable,
                    CustomFieldParameter::ShowInTable,
                    CustomFieldParameter::ShowInCard,
                ],
            ],

            'usage_type' => [
                'key' => 'data.attributes.usage_type',
                'name' => 'usage_type',
                'label' => __('vehicles.fields.usage_type'),
                'type' => CustomFieldType::SingleChoiceEnum,
                'required' => true,
                'options' => route('auxiliary.field-options', 'usage_type'),
                'parameters' => [
                    CustomFieldParameter::IsFilterableForCA,
                    CustomFieldParameter::IsFilterable,
                    CustomFieldParameter::ShowInTable,
                    CustomFieldParameter::ShowInCard,
                ],
            ],

            'vehicle_user_id' => [
                'key' => 'data.attributes.vehicle_user_id',
                'name' => 'vehicle_user_id',
                'label' => __('vehicles.fields.vehicle_user_id'),
                'type' => CustomFieldType::SingleChoiceEnum,
                'options' => route('auxiliary.field-options', 'vehicle_companies'),
                'parameters' => [
                    CustomFieldParameter::IsFilterableForCA,
                    CustomFieldParameter::IsFilterable,
                    CustomFieldParameter::ShowInTable,
                    CustomFieldParameter::ShowInCard,
                ],
            ],

            'rental_contract' => [
                'key' => 'data.attributes.rental_contract',
                'name' => 'rental_contract',
                'label' => __('vehicles.fields.rental_contract'),
                'type' => CustomFieldType::String,
                'rules' => [
                    'max:255',
                ],
                'parameters' => [
                    CustomFieldParameter::IsFilterable,
                    CustomFieldParameter::IsFilterableForCA,
                    CustomFieldParameter::ShowInTable,
                    CustomFieldParameter::ShowInCard,
                ],
            ],

            'rental_price' => [
                'key' => 'data.attributes.rental_price',
                'name' => 'rental_price',
                'label' => __('vehicles.fields.rental_price'),
                'type' => CustomFieldType::Float,
                'rules' => [
                    'numeric',
                    'min:0',
                ],
                'parameters' => [
                    CustomFieldParameter::ShowInCard,
                ],
            ],

            'technical_condition' => [
                'key' => 'data.attributes.technical_condition',
                'name' => 'technical_condition',
                'label' => __('vehicles.fields.technical_condition'),
                'type' => CustomFieldType::SingleChoiceEnum,
                'required' => true,
                'options' => route('auxiliary.field-options', 'technical_condition'),
                'parameters' => [
                    CustomFieldParameter::IsFilterableForCA,
                    CustomFieldParameter::IsFilterable,
                    CustomFieldParameter::ShowInTable,
                    CustomFieldParameter::ShowInCard,
                ],
            ],

            'action' => [
                'key' => 'data.attributes.action',
                'name' => 'action',
                'label' => __('vehicles.fields.action'),
                'type' => CustomFieldType::SingleChoiceEnum,
                'options' => route('auxiliary.field-options', 'action_type'),
                'parameters' => [
                    CustomFieldParameter::IsFilterableForCA,
                    CustomFieldParameter::IsFilterable,
                    CustomFieldParameter::ShowInCard,
                ],
            ],

            'notes' => [
                'key' => 'data.attributes.notes',
                'name' => 'notes',
                'label' => __('vehicles.fields.notes'),
                'type' => CustomFieldType::Text,
                'parameters' => [
                    CustomFieldParameter::ShowInCard,
                ],
            ],

            'insurance_expiration_date' => [
                'key' => 'data.attributes.insurance_expiration_date',
                'name' => 'insurance_expiration_date',
                'label' => __('vehicles.fields.insurance_expiration_date'),
                'type' => CustomFieldType::Date,
                'parameters' => [
                    CustomFieldParameter::IsFilterable,
                    CustomFieldParameter::IsFilterableForCA,
                    CustomFieldParameter::ShowInTable,
                    CustomFieldParameter::ShowInCard,
                ],
            ],

            'inspection_expiration_date' => [
                'key' => 'data.attributes.inspection_expiration_date',
                'name' => 'inspection_expiration_date',
                'label' => __('vehicles.fields.inspection_expiration_date'),
                'type' => CustomFieldType::Date,
                'parameters' => [
                    CustomFieldParameter::IsFilterable,
                    CustomFieldParameter::IsFilterableForCA,
                    CustomFieldParameter::ShowInTable,
                    CustomFieldParameter::ShowInCard,
                ],
            ],

            'inspection_date' => [
                'key' => 'data.attributes.inspection_date',
                'name' => 'inspection_date',
                'label' => __('vehicles.fields.inspection_date'),
                'type' => CustomFieldType::Date,
                'parameters' => [
                    CustomFieldParameter::IsFilterable,
                    CustomFieldParameter::IsFilterableForCA,
                    CustomFieldParameter::ShowInTable,
                    CustomFieldParameter::ShowInCard,
                ],
            ],

        ];
    }

    public function getReadableData(): array
    {
        return [
            'branch_office_id' => $this->branchOffice?->name,
            'brand' => $this->brand,
            'vehicle_type_id' => $this->vehicleType?->name,
            'plate_number' => $this->plate_number,
            'year' => $this->year,
            'location' => $this->location,
            'vehicle_owner_id' => $this->vehicleOwner?->name,
            'usage_type' => $this->usage_type?->name,
            'vehicle_user_id' => $this->vehicleUser?->name,
            'rental_contract' => $this->rental_contract,
            'rental_price' => $this->rental_price,
            'technical_condition' => $this->technical_condition?->name,
            'action' => $this->action?->name,
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
