<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class ClientCompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            $csvFile = fopen(base_path("database/data/companies_list.csv"), "r");

            Company::truncate();

            while (($data = fgetcsv($csvFile, 1000, ";")) !== FALSE) {
                Company::create([
                    'name' => $data[1],
                ]);
            }

            fclose($csvFile);

        } catch (\Exception $e) {
            Log::error('Error in ClientCompanySeeder: ' . $e->getMessage());
        }
    }
}
