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
        Permission::createOrFirst(['name' => 'departure_view']);
        Permission::createOrFirst(['name' => 'branch_update_create']);
        Permission::createOrFirst(['name' => 'branch_report_view']);
        Permission::createOrFirst(['name' => 'branch_report_create']);
        Permission::createOrFirst(['name' => 'user_register']);

        // update cache to know about the newly created permissions (required if using WithoutModelEvents in seeders)
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();


        // create roles and assign created permissions


        Role::createOrFirst(['name' => 'admin'])
            ->givePermissionTo(Permission::all());

        Role::createOrFirst(['name' => 'central_office_user'])
            ->givePermissionTo(['departure_view', 'branch_report_view']);

        Role::createOrFirst(['name' => 'branch_office_user'])
            ->givePermissionTo(['branch_update_create', 'branch_report_view', 'branch_report_create']);

        Role::createOrFirst(['name' => 'company_user']);

        Role::createOrFirst(['name' => 'ministry_user']);
    }
}
