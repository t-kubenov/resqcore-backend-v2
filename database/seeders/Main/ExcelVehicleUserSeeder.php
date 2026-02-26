<?php

namespace Database\Seeders\Main;

use App\Tenant\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ExcelVehicleUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $filePath = database_path('data/vehicle_users.csv');
        $roleId = 3;

        $permissions = [
            "view vehicle",
            "create vehicle",
            "update vehicle",
            "delete vehicle"
        ];

        if (!file_exists($filePath)) {
            $this->command->error("CSV file not found at: {$filePath}");
            return;
        }

        $file = fopen($filePath, 'r');

        // Read header row
        $headers = fgetcsv($file, 0, ';');

        // Map headers to indices
        $headerMap = array_flip($headers);

        $count = 0;

        while (($row = fgetcsv($file, 0, ';')) !== false) {
            $userData = [
                'first_name' => $row[$headerMap['Имя']],
                'last_name' => $row[$headerMap['Фамилия']],
                'patronymic' => !empty($row[$headerMap['Отчество']]) ? $row[$headerMap['Отчество']] : '',
                'email' => strtolower($row[$headerMap['E-Mail']]),
                'password' => Hash::make($row[$headerMap['Пароль']]),
                'role_id' => $roleId,
                'branch_id' => $row[$headerMap['ID Филиала']],
                'position' => !empty($row[$headerMap['Должность']]) ? $row[$headerMap['Должность']] : '',
            ];

            try {
                $user = User::firstOrCreate(['email' => $userData['email']], $userData);
                $user->syncPermissions($permissions);

                $count++;
                $this->command->info("Processed user: {$userData['email']}");

            } catch (\Exception $e) {
                $this->command->error("Failed to create user {$userData['email']}: {$e->getMessage()}");
            }
        }

        fclose($file);

        $this->command->info("Successfully created/gave permissions to {$count} users.");
    }
}
