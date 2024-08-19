<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BranchOfficeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $branchOffices = [
            'Актюбинский',
            'Атырауский',
            'Восточно-Казахстанкий',
            'Западно-Казахстанский',
            'Карагандинкский',
            'Кызылординский',
            'Мангистауский',
            'Павлодарский',
            'Северо-Казахстанский',
            'Южно-Казахстанский',
            'Акмолинский',
        ];

        foreach ($branchOffices as $branchOffice) {
            \App\Models\BranchOffice::create([
                'name' => $branchOffice,
            ]);
        }
    }
}
