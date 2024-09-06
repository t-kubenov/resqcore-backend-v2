<?php

namespace Database\Seeders;

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
            'ac' => 'АЦ',
            'operational_vehicle' => 'Оперативный а/м',
            'car_ladder' => 'Автолестница',
            'rpg_car' => 'Автомобиль РПГ',
            'shift_car' => 'Вахтовка',
            'light_car' => 'Легковой а/м',
            'light_car_high_cross_country' => 'Легковой а/м повышенной проходимости',
            'asa_car' => 'а/м АСА',
        ];

        foreach ($transportTypes as $key => $name) {
            \App\Models\TransportType::create([
                'key' => $key,
                'name' => $name,
            ]);
        }
    }
}
