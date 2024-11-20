<?php

namespace Database\Seeders;

use Database\Factories\TransportFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Transport;

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
