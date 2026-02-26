<?php

namespace Database\Seeders;

use App\Report\Update\Update;
use Illuminate\Database\Seeder;

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
