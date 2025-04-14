<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RescueCompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rescueCompanies = [
            ['key' => 'pvass', 'name' => 'Республиканский Центральный Штаб Профессиональных Военнизированных Аварийно-Спасательных Служб', 'alias' => 'РЦШ ПВАСС'],
            ['key' => 'semser', 'name' => 'ТОО "Семсер - Өрт Сөндіруші"', 'alias' => 'Семсер'],
            ['key' => 'seriktes', 'name' => 'Аварийно-спасательная служба "Серіктес"', 'alias' => 'Серіктес'],
        ];

        foreach ($rescueCompanies as $rescueCompany) {
            \App\Models\RescueCompany::create($rescueCompany);
        }
    }
}
