<?php

namespace Database\Seeders;

use App\Models\TransportType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransportTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $transportTypes = [
            [
                'key' => 'ac',
                'name' => 'АЦ',
            ],
            [
                'key' => 'operational_vehicle',
                'name' => 'Оперативный а/м',
            ],
            [
                'key' => 'car_ladder',
                'name' => 'Автолестница',
            ],
            [
                'key' => 'rpg_car',
                'name' => 'Автомобиль РПГ',
            ],
            [
                'key' => 'shift_car',
                'name' => 'Вахтовка',
            ],
            [
                'key' => 'light_car',
                'name' => 'Легковой а/м',
            ],
            [
                'key' => 'light_car_high_cross_country',
                'name' => 'Легковой а/м повышенной проходимости',
                'alias' => 'Легковой а/м ПП',
            ],
            [
                'key' => 'asa_car',
                'name' => 'а/м АСА',
            ],
        ];

        foreach ($transportTypes as $transportType) {
            TransportType::updateOrCreate(
                ['name' => $transportType['name']], // Matching condition
                [
                    'key' => $transportType['key'] ?? null,
                    'alias' => $transportType['alias'] ?? null, // Use null if alias is not set
                ]
            );
        }
    }
}
