<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Update;

class UpdateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Update::factory()->count(50)->create();
        Update::factory()->isClosingUpdate()->count(50)->create();
    }
}
