<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmergencyTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $emergencyTypes = [
            'fire' => 'Пожар',
            'injury' => 'Травма',
            'blackout' => 'Отключение электроэнергии',
            'collapse' => 'Обрушение',
            'accident' => 'Несчастный случай',
            'stuck' => 'Застревание',
            'disease' => 'Заболевание',
            'flood' => 'Затопление',
            'traffic_accident' => 'ДТП',
            'gas' => 'Загазованность',
            'explosion' => 'Взрыв',
            'provision' => 'Обеспечение',
            'people_search' => 'Поиск людей',
            'open_fountain' => 'Открытый фонтан',
            'spill' => 'Розлив',
            'other' => 'Иное',
        ];

        foreach ($emergencyTypes as $key => $name) {
            \App\Models\EmergencyType::create([
                'key' => $key,
                'name' => $name,
            ]);
        }
    }
}
