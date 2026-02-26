<?php

namespace Database\Seeders;

use App\Tenant\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestCompanyUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'first_name' => 'Test',
            'last_name' => 'Testov',
            'patronymic' => 'Testovich',
            'email' => 'test@resq.kz',
            'password' => Hash::make(config('app.etc.admin_password')),
            'role_id' => 2,
            'branch_id' => 17,
        ])->assignRole('central_office_user');

        User::create([
            'first_name' => 'Ris',
            'last_name' => 'Risov',
            'patronymic' => 'Risovich',
            'email' => 'ris@resq.kz',
            'password' => Hash::make(config('app.etc.admin_password')),
            'role_id' => 2,
            'branch_id' => 18,
        ])->assignRole('branch_office_user');

        User::create([
            'first_name' => 'Flis',
            'last_name' => 'Flisov',
            'patronymic' => 'Flisovich',
            'email' => 'flis@resq.kz',
            'password' => Hash::make(config('app.etc.admin_password')),
            'role_id' => 2,
            'branch_id' => 19,
        ])->assignRole('branch_office_user');
    }

}
