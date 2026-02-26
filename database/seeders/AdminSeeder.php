<?php

namespace Database\Seeders;

use App\Tenant\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'first_name' => 'Admin',
            'last_name' => 'ResQ',
            'patronymic' => 'Petrovich',
            'email' => 'admin@resq.kz',
            'password' => Hash::make(config('app.etc.admin_password')),
            'role_id' => 1,
            'branch_id' => 1,
            'company_id' => 1,
        ]);

        $user->assignRole('admin');
    }
}
