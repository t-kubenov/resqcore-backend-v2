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

        $central_offices = BranchOffice::where('is_central_office', true)->get();
        $branch_offices = BranchOffice::where('is_central_office', false)->get();

        foreach ($central_offices as $branch_office) {
            User::factory([
                'branch_id' => $branch_office->id,
                'role_id' => 2,
            ])->count(3)->create();
        }

        foreach ($branch_offices as $branch_office) {
            User::factory([
                'branch_id' => $branch_office->id,
                'role_id' => 3,
            ])->count(3)->create();
        }
    }
}
