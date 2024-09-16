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
            'departure_view' => 'central_office',
            'branch_report_view' => 'branch_office',
            'branch_report_create' => 'branch_office',
            'branch_update_create' => 'branch_office',
        ];

        foreach ($permissions as $permission => $guard) {
            Permission::create([
                'name' => $permission,
                'guard_name' => $guard,
            ]);
        }
    }
}
