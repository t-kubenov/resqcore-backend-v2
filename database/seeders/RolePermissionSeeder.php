<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'departure_view']);
        Permission::create(['name' => 'branch_update_create']);
        Permission::create(['name' => 'branch_report_view']);
        Permission::create(['name' => 'branch_report_create']);
        Permission::create(['name' => 'user_register']);

        // update cache to know about the newly created permissions (required if using WithoutModelEvents in seeders)
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();


        // create roles and assign created permissions


        Role::create(['name' => 'admin'])
            ->givePermissionTo(Permission::all());

        Role::create(['name' => 'central_office_user'])
            ->givePermissionTo(['departure_view', 'branch_report_view']);

        Role::create(['name' => 'branch_office_user'])
            ->givePermissionTo(['branch_update_create', 'branch_report_view', 'branch_report_create']);

        Role::create(['name' => 'company_user']);

        Role::create(['name' => 'ministry_user']);
    }
}
