<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rolePermissions = [
            'admin' => [
                'is_admin',
            ],
            'central_office_user' => [
                'is_central_office_user',
            ],
            'branch_office_user' => [
                'is_branch_office_user',
            ],
            'company_user' => [
                'is_company_user',
            ],
            'ministry_user' => [
                'is_ministry_user',
            ],
        ];

        foreach ($rolePermissions as $role => $permissions) {
            $role = Role::where('name', $role)->first();

            foreach ($permissions as $permission) {
                $permission = Permission::where('name', $permission)->first();
                $role->permissions()->attach($permission);
            }
        }
    }
}
