<?php

namespace Database\Seeders;

use App\Models\EmergencyType;
use Illuminate\Database\Seeder;

class EmergencyTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rescueCompanies = \App\Models\RescueCompany::all();

        foreach ($rescueCompanies as $rescueCompany) {
            EmergencyType::factory()->count(7)->create([
                'rescue_company_id' => $rescueCompany->id,
            ]);
        }
    }
}
