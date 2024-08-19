<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\OperationUpdate;

class OperationUpdateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        OperationUpdate::factory()->count(5)->create();
        OperationUpdate::factory()->isClosingUpdate()->count(5)->create();
    }
}
