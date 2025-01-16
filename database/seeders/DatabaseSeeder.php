<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            BranchOfficeSeeder::class,
            EmergencyTypeSeeder::class,
            TransportTypeSeeder::class,
            RolePermissionSeeder::class,
            UserSeeder::class,

            Egov\SubsoilContractSeeder::class,
            Egov\MineralFieldSeeder::class,
            Egov\OilFieldSeeder::class,

            ReportSeeder::class,
            UpdateSeeder::class,
            TransportSeeder::class,
        ]);
    }
}
