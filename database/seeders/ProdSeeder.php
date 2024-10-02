<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProdSeeder extends Seeder
{
    /**
     * Run the database seeds.
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
            ClientCompanySeeder::class,
        ]);
    }
}
