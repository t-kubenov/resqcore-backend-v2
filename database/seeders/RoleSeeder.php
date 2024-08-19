<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            'admin',
            'central_office_user',
            'branch_office_user',
            'company_user',
            'ministry_user',
        ];

        foreach ($roles as $role) {
            Role::create([
                'name' => $role,
                'guard_name' => $role,
            ]);
        }
    }
}
