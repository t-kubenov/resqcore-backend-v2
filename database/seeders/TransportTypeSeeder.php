<?php

namespace Database\Seeders;

use App\Models\TransportType;
use Illuminate\Database\Seeder;

class TransportTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rescueCompanies = \App\Models\RescueCompany::all();

        foreach ($rescueCompanies as $rescueCompany) {
            TransportType::factory()->count(7)->create([
                'rescue_company_id' => $rescueCompany->id,
            ]);
        }
    }
}
