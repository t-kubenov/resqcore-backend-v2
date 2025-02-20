<?php

namespace Database\Seeders;

use App\Models\BranchOffice;
use App\Models\RescueCompany;
use Illuminate\Database\Seeder;

class BranchOfficeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $branchOffices = [
            'pvass' => [
                [
                    'name' => 'Центральный Аппарат ПВАСС',
                    'key' => 'pvass-central-office',
                    'alias' => 'ЦА ПВАСС',
                    'is_central_office' => true,
                ],
                [
                    'name' => 'Актюбинский',
                    'key' => 'aktobe',
                ],
                [
                    'name' => 'Атырауский',
                    'key' => 'atyrau',
                ],
                [
                    'name' => 'Восточно-Казахстанский',
                    'alias' => 'ВКФ',
                    'key' => 'vkf',
                ],
                [
                    'name' => 'Западно-Казахстанский',
                    'alias' => 'ЗКФ',
                    'key' => 'zkf',
                ],
                [
                    'name' => 'Карагандинский',
                    'key' => 'karaganda',
                ],
                [
                    'name' => 'Кызылординский',
                    'key' => 'kyzylorda',
                ],
                [
                    'name' => 'Мангистауский',
                    'key' => 'mangystau',
                ],
                [
                    'name' => 'Павлодарский',
                    'key' => 'pavlodar',
                ],
                [
                    'name' => 'Северо-Казахстанский',
                    'alias' => 'СКФ',
                    'key' => 'skf',
                ],
                [
                    'name' => 'Южно-Казахстанский',
                    'alias' => 'ЮКФ',
                    'key' => 'yukf',
                ],
                [
                    'name' => 'Акмолинский',
                    'key' => 'akmolinsk',
                ],
            ],
            'semser' =>
                [
                    [
                        'name' => 'ЦА "Семсер"',
                        'key' => 'semser',
                        'is_central_office' => true,
                    ],
                    [
                        'name' => 'Филиал "Семсер"',
                        'key' => 'branch_semser',
                    ],
                ],
            'seriktes' =>
                [
                    [
                        'name' => 'ЦА "Сериктес"',
                        'key' => 'seriktes',
                        'is_central_office' => true,
                    ],
                    [
                        'name' => 'Филиал "Сериктес"',
                        'key' => 'branch_seriktes',
                    ],
                ],
            'test' =>
                [
                    [
                        'name' => 'Тестовый',
                        'key' => 'test',
                        'is_central_office' => true,
                    ],
                    [
                        'name' => 'Рисовый',
                        'key' => 'rice',
                    ],
                    [
                        'name' => 'Флисовый',
                        'key' => 'fleece',
                    ],
                ],
        ];

        foreach ($branchOffices as $rescueCompany => $branchOffice) {
            foreach ($branchOffice as $office) {
                BranchOffice::updateOrCreate(
                    [
                        'rescue_company_id' => RescueCompany::where('key', $rescueCompany)->first()['id'],
                        'name' => $office['name']
                    ],
                    [
                        'key' => $office['key'] ?? null,
                        'alias' => $office['alias'] ?? null,
                        'is_central_office' => $office['is_central_office'] ?? false,
                    ]
                );
            }
        }
    }
}
