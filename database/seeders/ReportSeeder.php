<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Report;
use App\Models\BranchOffice;

class ReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (BranchOffice::all() as $branch_office) {
            Report::factory()->count(10)->create([
                'branch_office_id' => $branch_office->id,
            ]);
            Report::factory()->isClosed()->count(10)->create([
                'branch_office_id' => $branch_office->id,
            ]);
        }
    }
}
