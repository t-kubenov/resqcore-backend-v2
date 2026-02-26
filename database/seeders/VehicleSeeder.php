<?php

namespace Database\Seeders;

use App\Vehicle\Vehicle;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Vehicle::factory()->count(50)->create();
    }
}
