<?php

namespace Database\Seeders;

use App\Report\Transport\Transport;
use Illuminate\Database\Seeder;

class TransportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Transport::factory()->isReportTransport()->count(50)->create();
        Transport::factory()->isUpdateTransport()->count(50)->create();
    }
}
