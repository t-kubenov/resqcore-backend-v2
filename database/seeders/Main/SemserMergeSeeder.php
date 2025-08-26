<?php

namespace Database\Seeders\Main;

use App\Enums\CustomFieldType;
use App\Models\BranchOffice;
use App\Models\EmergencyType;
use App\Models\TransportType;
use App\Models\User;
use App\Models\CustomField\CustomField;
use App\Models\Report\Report;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SemserMergeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement("SELECT setval('branch_offices_id_seq', (SELECT MAX(id) FROM branch_offices));");

        $this->createAndAssignPVASSCA();
        // Semser:
        // branch offices
        $this->createBranchOffices();
        // TransportType
        $this->createTransportTypes();
        // emergency types
        $this->createEmergencyTypes();
        // custom fields
        $this->createCustomFields();
    }

    private function createCustomFields()
    {
        $customFields = [
            [
                'rescue_company_id' => 2,
                'name' => 'semser_fire_station',
                'label' => 'Пожарная часть',
                'model' => Report::class,
                'required' => true,
                'type' => CustomFieldType::String,
            ],
            [
                'rescue_company_id' => 2,
                'name' => 'semser_injured_workers',
                'label' => 'Пострадавшие рабочие',
                'model' => Report::class,
                'required' => false,
                'type' => CustomFieldType::Text,
            ],
            [
                'rescue_company_id' => 2,
                'name' => 'semser_water_used',
                'label' => 'Использовано воды (литры)',
                'model' => Report::class,
                'required' => false,
                'type' => CustomFieldType::Float,
            ],
            [
                'rescue_company_id' => 2,
                'name' => 'semser_foam_used',
                'label' => 'Использовано пены (литры)',
                'model' => Report::class,
                'required' => false,
                'type' => CustomFieldType::Float,
            ],
            [
                'rescue_company_id' => 2,
                'name' => 'semser_total_distance_to_scene',
                'label' => 'Общее расстояние (км)',
                'model' => Report::class,
                'required' => false,
                'type' => CustomFieldType::Float,
                'validation' => 'min:0',
            ],
            [
                'rescue_company_id' => 2,
                'name' => 'semser_false_call',
                'label' => 'Ложный вызов',
                'model' => Report::class,
                'required' => true,
                'type' => CustomFieldType::Boolean,
            ],
        ];

        foreach ($customFields as $field) {
            CustomField::create($field);
        }
    }


    private function createEmergencyTypes(){
        $emergencyTypes = [
            ['key' => 'semser_fire', 'name' => 'Пожар'],
            ['key' => 'semser_smoke', 'name' => 'Задымление'],
            ['key' => 'semser_ignition', 'name' => 'Возгорание'],
            ['key' => 'semser_collapse', 'name' => 'Обрушение'],
            ['key' => 'semser_spill', 'name' => 'Разлив'],
            ['key' => 'semser_gassing', 'name' => 'Загазованность'],
        ];

        foreach ($emergencyTypes as $emergencyType) {
            EmergencyType::updateOrCreate(
                [
                    'key' => $emergencyType['key']
                ], // Matching condition
                [
                    'name' => $emergencyType['name'] ?? null,
                    'alias' => $emergencyType['alias'] ?? null, // Use null if alias is not set
                    'rescue_company_id' => 2,
                ]
            );
        }
    }

    private function createTransportTypes(){
        $transportTypes = [
            [
                'key' => 'semser_fire_truck',
                'name' => 'Автоцистерна пожарная (АЦ)',
            ],
            [
                'key' => 'semser_ladder_truck',
                'name' => 'Автолестница (АЛ)',
            ],
            [
                'key' => 'semser_pump_truck',
                'name' => 'Пожарно-насосная станция (ПНС)',
            ],
            [
                'key' => 'semser_rescue_truck',
                'name' => 'Аварийно-спасательный автомобиль (АСА)',
            ],
            [
                'key' => 'semser_quick_response_truck',
                'name' => 'Автомобили быстрого реагирования (АБР)',
            ],
            [
                'key' => 'semser_command_truck',
                'name' => 'Штабной',
            ],
            [
                'key' => 'semser_gas_rescue_truck',
                'name' => 'Газоспасательный',
            ],
            [
                'key' => 'semser_special_truck',
                'name' => 'Специальная',
            ],
            [
                'key' => 'semser_operational_truck',
                'name' => 'Оперативная',
            ],
            [
                'key' => 'semser_passenger_car',
                'name' => 'Легковая',
            ],
            [
                'key' => 'semser_shift_car',
                'name' => 'Вахтовая',
            ],
            [
                'key' => 'semser_auxiliary_car',
                'name' => 'Вспомогательная',
            ],
        ];


        foreach ($transportTypes as $transportType) {
            TransportType::updateOrCreate(
                [
                    'key' => $transportType['key']
                ], // Matching condition
                [
                    'name' => $transportType['name'] ?? null,
                    'alias' => $transportType['alias'] ?? null, // Use null if alias is not set
                    'rescue_company_id' => 2,
                ]
            );
        }
    }

    private function createBranchOffices()
    {
        $transportTypes = [
            [
                'key' => 'semser_central_office',
                'name' => 'ЦА Семсер',
                'is_central_office' => true,
            ],
            [
                'key' => 'semser_ural',
                'name' => 'Уральский филиал',
            ],
            [
                'key' => 'semser_zapad',
                'name' => 'Западный филиал',
            ],
            [
                'key' => 'semser_aktubinsk',
                'name' => 'Актюбинский филиал',
            ],
            [
                'key' => 'semser_pavlodar',
                'name' => 'Павлодарский филиал',
            ],
            [
                'key' => 'semser_mangystau',
                'name' => 'Мангистауский филиал',
            ],
            [
                'key' => 'semser_karaganda',
                'name' => 'Карагандинский филиал',
            ],
            [
                'key' => 'semser_south_kz',
                'name' => 'Южно-Казахстанский филиал',
            ],
            [
                'key' => 'semser_kyzylorda',
                'name' => 'Кызылординский филиал',
            ],
            [
                'key' => 'semser_zhanaozen',
                'name' => 'Жанаозенский филиал',
            ],
            [
                'key' => 'semser_astana',
                'name' => 'Астанинский филиал',
            ],
            [
                'key' => 'semser_almaty',
                'name' => 'Алматинский филиал',
            ],
        ];

        foreach ($transportTypes as $transportType) {
            BranchOffice::updateOrCreate(
                [
                    'key' => $transportType['key']
                ], // Matching condition
                [
                    'name' => $transportType['name'] ?? null,
                    'alias' => $transportType['alias'] ?? null, // Use null if alias is not set
                    'is_central_office' => $transportType['is_central_office'] ?? false,
                    'rescue_company_id' => 2,
                ]
            );
        }
    }

    private function createAndAssignPVASSCA(): void
    {
        $branch = BranchOffice::updateOrCreate(
            [
                'key' => 'pvass_ca',
            ],
            [
                'alias' => 'ЦА ПВАСС',
                'name' => 'Центральный Аппарат ПВАСС',
                'rescue_company_id' => 1,
                'is_central_office' => true,
        ]);

        User::where('role_id', 2)
            ->whereNull('branch_id')
            ->update(['branch_id' => $branch->id]);
    }
}
