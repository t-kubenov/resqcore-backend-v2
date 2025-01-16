<?php

namespace Database\Seeders;

use App\Models\BranchOffice;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call(AdminSeeder::class);

        User::factory()->central_office_user()->count(3)->create();

        foreach (BranchOffice::all() as $branch_office) {
            User::factory()->branch_office_employee()->create([
                'branch_id' => $branch_office->id,
            ]);
        }

        User::factory()->company_employee()->count(10)->create();
    }
}
