<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
//         Аварийно-спасательная служба
// Центральный Аппарат (просмотр, все филиалы)
// Сотрудник опер. группы (просмотр, редактирование по филиалам)

// Недропользователи
// БиОТ (просмотр, подпись по своим объектам)
// Ответ на предписание

// МЧС
// Сотрудник МЧС (просмотр, все объекты, подпись по мере ознакомления)

        $permissions = [
            // 'view_all_operations' => 'central_office',
            // 'edit_all_operations' => 'admin',
            // 'view_own_operation' => 'branch_office',
            // 'edit_own_operation' => 'branch_office',
            // 'view_all_facilities' => 'the_ministry',
            // 'edit_all_facilities' => 'admin',
            // 'sign_all_facilities' => 'the_ministry',
            // 'view_own_facility' => 'company',
            // 'edit_own_facility' => 'company',
            // 'sign_own_facility' => 'company',

            'is_admin' => 'admin',
            'is_central_office_user' => 'central_office_user',
            'is_branch_office_user' => 'branch_office_user',
            'is_company_user' => 'company_user',
            'is_ministry_user' => 'ministry_user',
        ];

        foreach ($permissions as $permission => $guard) {
            Permission::create([
                'name' => $permission,
                'guard_name' => $guard,
            ]);
        }
    }
}
