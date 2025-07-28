<?php

namespace Database\Seeders;

use App\Enums\CustomFieldType;
use App\Models\BranchOffice;
use App\Models\EmergencyType;
use App\Models\TransportType;
use App\Models\User;
use App\Models\V2\CustomField\CustomField;
use App\Models\V2\Report\Report;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SemserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\RescueCompany::create(
            ['id' => 2, 'key' => 'semser', 'name' => 'ТОО "Семсер - Өрт Сөндіруші"', 'alias' => 'Семсер'],
        );

        // TransportType
        $this->createTransportTypes();

        // branch offices
        $this->createBranchOffices();

        // emergency types
        $this->createEmergencyTypes();

        // custom fields
        $this->createCustomFields();

        // roles and permissions
        $this->call([
            RolePermissionSeeder::class
        ]);

        // admin
        $user = User::create([
            'first_name' => 'Admin',
            'last_name' => 'ResQ',
            'patronymic' => 'Petrovich',
            'email' => 'admin@resq.kz',
            'password' => Hash::make(config('app.etc.admin_password')),
            'role_id' => 1,
            'branch_id' => 1,
            'company_id' => 2,
        ]);

        $user->assignRole('admin');

        // custom field options
        // report creation test
        // report data display test



    }

    private function createCustomFields()
    {
        $customFields = [
            [
                'rescue_company_id' => 2,
                'name' => 'fire_station',
                'label' => 'Пожарная часть',
                'model' => Report::class,
                'required' => true,
                'type' => CustomFieldType::String,
            ],
            [
                'rescue_company_id' => 2,
                'name' => 'injured_workers',
                'label' => 'Пострадавшие рабочие',
                'model' => Report::class,
                'required' => false,
                'type' => CustomFieldType::Text,
            ],
            [
                'rescue_company_id' => 2,
                'name' => 'water_used',
                'label' => 'Использовано воды (литры)',
                'model' => Report::class,
                'required' => false,
                'type' => CustomFieldType::Float,
            ],
            [
                'rescue_company_id' => 2,
                'name' => 'foam_used',
                'label' => 'Использовано пены (литры)',
                'model' => Report::class,
                'required' => false,
                'type' => CustomFieldType::Float,
            ],
            [
                'rescue_company_id' => 2,
                'name' => 'distance_to_scene',
                'label' => 'Расстояние до места происшествия (км)',
                'model' => Report::class,
                'required' => false,
                'type' => CustomFieldType::Float,
                'validation' => 'min:0',
            ],
            [
                'rescue_company_id' => 2,
                'name' => 'false_call',
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
            ['key' => 'fire', 'name' => 'Пожар'],
            ['key' => 'smoke', 'name' => 'Задымление'],
            ['key' => 'ignition', 'name' => 'Возгорание'],
            ['key' => 'collapse', 'name' => 'Обрушение'],
            ['key' => 'spill', 'name' => 'Разлив'],
            ['key' => 'gassing', 'name' => 'Загазованность'],
        ];

        foreach ($emergencyTypes as $emergencyType) {
            EmergencyType::updateOrCreate(
                ['name' => $emergencyType['name']], // Matching condition
                [
                    'key' => $emergencyType['key'] ?? null,
                    'alias' => $emergencyType['alias'] ?? null, // Use null if alias is not set
                    'rescue_company_id' => 2,
                ]
            );
        }
    }

    private function createTransportTypes(){
        $transportTypes = [
            [
                'key' => 'fire_truck',
                'name' => 'Автоцистерна пожарная (АЦ)',
            ],
            [
                'key' => 'ladder_truck',
                'name' => 'Автолестница (АЛ)',
            ],
            [
                'key' => 'pump_truck',
                'name' => 'Пожарно-насосная станция (ПНС)',
            ],
            [
                'key' => 'rescue_truck',
                'name' => 'Аварийно-спасательный автомобиль (АСА)',
            ],
            [
                'key' => 'quick_response_truck',
                'name' => 'Автомобили быстрого реагирования (АБР)',
            ],
            [
                'key' => 'command_truck',
                'name' => 'Штабной',
            ],
            [
                'key' => 'gas_rescue_truck',
                'name' => 'Газоспасательный',
            ],
            [
                'key' => 'special_truck',
                'name' => 'Специальная',
            ],
            [
                'key' => 'operational_truck',
                'name' => 'Оперативная',
            ],
            [
                'key' => 'passenger_car',
                'name' => 'Легковая',
            ],
            [
                'key' => 'shift_car',
                'name' => 'Вахтовая',
            ],
            [
                'key' => 'auxiliary_car',
                'name' => 'Вспомогательная',
            ],
        ];


        foreach ($transportTypes as $transportType) {
            TransportType::updateOrCreate(
                ['name' => $transportType['name']], // Matching condition
                [
                    'key' => $transportType['key'] ?? null,
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
                'name' => 'ЦА Семсер'
            ],
            [
                'key' => 'ural',
                'name' => 'Уральский филиал',
            ],
            [
                'key' => 'zapad',
                'name' => 'Западный филиал',
            ],
            [
                'key' => 'aktubinsk',
                'name' => 'Актюбинский филиал',
            ],
            [
                'key' => 'pavlodar',
                'name' => 'Павлодарский филиал',
            ],
            [
                'key' => 'mangystau',
                'name' => 'Мангистауский филиал',
            ],
            [
                'key' => 'karaganda',
                'name' => 'Карагандинский филиал',
            ],
            [
                'key' => 'south_kz',
                'name' => 'Южно-Казахстанский филиал',
            ],
            [
                'key' => 'kyzylorda',
                'name' => 'Кызылординский филиал',
            ],
            [
                'key' => 'zhanaozen',
                'name' => 'Жанаозенский филиал',
            ],
            [
                'key' => 'astana',
                'name' => 'Астанинский филиал',
            ],
            [
                'key' => 'almaty',
                'name' => 'Алматинский филиал',
            ],
        ];

        foreach ($transportTypes as $transportType) {
            BranchOffice::updateOrCreate(
                ['name' => $transportType['name']], // Matching condition
                [
                    'key' => $transportType['key'] ?? null,
                    'alias' => $transportType['alias'] ?? null, // Use null if alias is not set
                    'rescue_company_id' => 2,
                ]
            );
        }
    }
}
