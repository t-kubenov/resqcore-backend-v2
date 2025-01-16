<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class EmergencyTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $emergencyTypes = [
            [
                'key' => 'fire',
                'name' => 'Пожар',
            ],
            [
                'key' => 'injury',
                'name' => 'Травма',
            ],
            [
                'key' => 'blackout',
                'name' => 'Отключение электроэнергии',
                'alias' => 'Откл. электр.',
            ],
            [
                'key' => 'collapse',
                'name' => 'Обрушение',
            ],
            [
                'key' => 'accident',
                'name' => 'Несчастный случай',
                'alias' => 'НС',
            ],
            [
                'key' => 'stuck',
                'name' => 'Застревание',
            ],
            [
                'key' => 'disease',
                'name' => 'Заболевание',
            ],
            [
                'key' => 'flood',
                'name' => 'Затопление',
            ],
            [
                'key' => 'traffic_accident',
                'name' => 'ДТП',
            ],
            [
                'key' => 'gas',
                'name' => 'Загазованность',
                'alias' => 'Газ',
            ],
            [
                'key' => 'explosion',
                'name' => 'Взрыв',
            ],
            [
                'key' => 'provision',
                'name' => 'Обеспечение',
            ],
            [
                'key' => 'people_search',
                'name' => 'Поиск людей',
            ],
            [
                'key' => 'open_fountain',
                'name' => 'Открытый фонтан',
                'alias' => 'Фонтан',
            ],
            [
                'key' => 'spill',
                'name' => 'Розлив',
            ],
            [
                'key' => 'other',
                'name' => 'Иное',
            ],
            [
                'key' => 'fall',
                'name' => 'Падение с высоты',
                'alias' => 'Падение',
            ]

        ];

        foreach ($emergencyTypes as $emergencyType) {
            \App\Models\EmergencyType::updateOrCreate(
                ['name' => $emergencyType['name']], // Matching condition
                [
                    'key' => $emergencyType['key'],
                    'alias' => $emergencyType['alias'] ?? null,
                ]
            );
        }
    }
}
