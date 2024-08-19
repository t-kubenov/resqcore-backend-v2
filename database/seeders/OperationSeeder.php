<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Operation;

class OperationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Operation::factory()->isEmergencyLineOperation()->count(5)->create();
        Operation::factory()->isContractOperation()->count(5)->create();
    }
}
