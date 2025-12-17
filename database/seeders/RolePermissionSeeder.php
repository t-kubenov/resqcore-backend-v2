<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        DB::table('permissions')->truncate();

        $permissions = [
            'view report',
            'create report',
            'update report',
            'delete report',

            'view survey',
            'create survey',
            'update survey',
            'delete survey',

            'view vehicle',
            'create vehicle',
            'update vehicle',
            'delete vehicle',
        ];

        $central_office_permissions = [
            'view report',
            'view survey',
        ];

        $branch_office_permissions = [
            'view report',
            'create report',

            'view survey',
            'create survey',
        ];

        // create permissions
        foreach ($permissions as $permission) {
            Permission::createOrFirst(['name' => $permission]);
        }

        // update cache to know about the newly created permissions (required if using WithoutModelEvents in seeders)
        app()[PermissionRegistrar::class]->forgetCachedPermissions();


        Role::createOrFirst(['name' => 'admin'])
            ->givePermissionTo(Permission::all());

        Role::createOrFirst(['name' => 'central_office_user'])
            ->givePermissionTo($central_office_permissions);

        Role::createOrFirst(['name' => 'branch_office_user'])
            ->givePermissionTo($branch_office_permissions);
    }
}
