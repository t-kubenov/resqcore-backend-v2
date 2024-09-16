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
                'departure_view',
                'branch_report_view',
                'branch_report_create',
                'branch_update_create',
            ],

            'central_office_user' => [
                'departure_view',
            ],

            'branch_office_user' => [
                'branch_report_view',
                'branch_report_create',
                'branch_update_create',
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
