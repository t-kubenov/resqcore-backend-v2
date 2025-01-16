<?php

namespace Database\Seeders;

use App\Models\BranchOffice;
use Illuminate\Database\Seeder;

class BranchOfficeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $branchOffices = [
            [
                'name' => 'Актюбинский',
                'key' => 'aktobe',
            ],
            [
                'name' => 'Атырауский',
                'key' => 'atyrau',
            ],
            [
                'name' => 'Восточно-Казахстанкий',
                'alias' => 'ВКФ',
                'key' => 'vkf',
            ],
            [
                'name' => 'Западно-Казахстанский',
                'alias' => 'ЗКФ',
                'key' => 'zkf',
            ],
            [
                'name' => 'Карагандинкский',
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

        ];

        foreach ($branchOffices as $branchOffice) {
            BranchOffice::updateOrCreate(
                ['name' => $branchOffice['name']], // Matching condition
                [
                    'key' => $branchOffice['key'] ?? null,
                    'alias' => $branchOffice['alias'] ?? null, // Use null if alias is not set
                ]
            );
        }
    }
}
