<?php

namespace Database\Seeders\Main;

use App\Enums\Vehicle\ActionType;
use App\Enums\Vehicle\TechnicalCondition;
use App\Enums\Vehicle\UsageType;
use App\Models\Vehicle\Vehicle;
use App\Models\Vehicle\VehicleCompany;
use App\Models\Vehicle\VehicleType;
use Carbon\Carbon;
use DateTime;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExcelVehicleSeeder extends Seeder
{
    private array $branchOfficeMap = [
        'Акмолинский' => 11,
        'Актюбинский' => 1,
        'Атырауский' => 2,
        'Восточно-Казахстанский' => 3,
        'Западно-Казахстанский' => 4,
        'Карагандинский' => 5,
        'Кызылординский' => 6,
        'Мангистауский' => 7,
        'Павлодарский' => 8,
        'Северо-Казахстанский' => 9,
        'ТОО "АСС "Серiктес"' => 12,
        'Южно-Казахстанский' => 10,
    ];

    private array $usageTypeMap = [
        'Собственность' => UsageType::Owned,
        'Собственность заказчика' => UsageType::CustomerOwned,
        'Аренда у заказчика' => UsageType::CustomerRental,
        'Аренда' => UsageType::Rental,
        'Собственность передан в аренду' => UsageType::TransferredOwnership,
    ];

    private array $actionMap = [
        'ПЕРЕМЕШЕНИЕ' => ActionType::Transfer,
        'ПРИОБРЕТЕН' => ActionType::Acquired,
        'НА РЕАЛИЗАЦИЮ' => ActionType::ForSale,
        'РЕЗЕРВ' => ActionType::Reserved,
        '' => ActionType::None,
    ];

    private array $conditionMap = [
        'исправен' => 0,
        'Не исправен' => 1,
    ];

    private function openFile($filePath)
    {
        if (!file_exists($filePath)) {
            $this->command->error("CSV file not found at: $filePath");
        }

        if (!($handle = fopen($filePath, 'r'))) {
            $this->command->error("Error reading file");
        }

        return $handle;

    }

    private function tidyString(string $string): string
    {
        $string = trim($string);

        // Replace multiple spaces with a single space
        $string = preg_replace('/\s+/', ' ', $string);

        // Capitalise first letter, lowercase the rest (UTF-8 safe)
        return mb_ucfirst($string, 'utf-8');
    }

    private function readRowsAndTypes($handle): array
    {
        $header = fgetcsv($handle, 1000, ';'); // read header row

        $csvRows = [];
        $vehicleTypes = [];
        $vehicleOwners = [];
        $vehicleUsers = [];

        while (($data = fgetcsv($handle, 1000, ';')) !== false) {
            $row = array_combine($header, $data);

            $csvRows[] = $row;

            $vehicleTypes[] = $this->tidyString($row['vehicle_type']);
            $vehicleOwners[] = $this->tidyString($row['vehicle_owner']);
            $vehicleUsers[] = $this->tidyString($row['vehicle_user']);

        }
        fclose($handle);

        return [
            'rows' => $csvRows,
            'vehicleTypes' => $vehicleTypes,
            'vehicleOwners' => $vehicleOwners,
            'vehicleUsers' => $vehicleUsers,
        ];
    }

    private function createTypes(string $model, array $array): array
    {
        $map = [];
        foreach (array_unique($array) as $typeName) {
            $type = $model::firstOrCreate([
                'name' => $typeName,
                'rescue_company_id' => 1,
            ]);
            $map[$typeName] = $type->id;
        }

        return $map;
    }

    private function formatDate(string $dateString): DateTime
    {
        $dateString = $this->tidyNumeric($dateString);

        return Carbon::parse($dateString)->toDate();
    }

    private function tidyNumeric(string $numString): string|null
    {
        $numString = preg_replace('/[^0-9.]/', '', $numString);

        return $numString?: null;
    }

    private function createVehicles($rows, $typeMap, $companyMap): array
    {
        // Prepare vehicles for bulk insert
        $vehicleRows = [];
        foreach ($rows as $row) {
            $vehicleRows[] = [
                'branch_office_id'          => $this->branchOfficeMap[trim($row['branch_office'])],
                'vehicle_type_id'           => $typeMap[$this->tidyString($row['vehicle_type'])],
                'vehicle_owner_id'          => $companyMap[$this->tidyString($row['vehicle_owner'])] ?: null,
                'usage_type'                => $this->usageTypeMap[trim($row['usage_type'])]?: UsageType::Owned,
                'vehicle_user_id'           => $companyMap[$this->tidyString($row['vehicle_user'])] ?: null,
                'technical_condition'       => $this->conditionMap[trim($row['technical_condition'])] ?: TechnicalCondition::Operational,
                'action'                    => $this->actionMap[trim($row['action'])] ?: ActionType::None,

                'rental_price'              => $row['rental_price']? $this->tidyNumeric($row['rental_price']): null,

                'brand'                     => $row['brand'] ?: null,
                'plate_number'              => $row['plate_number'] ?: null,
                'year'                      => $row['year'] ?: null,
                'location'                  => $row['location'] ?: null,
                'rental_contract'           => $row['rental_contract'] ?: null,
                'notes'                     => $row['notes'] ?: null,

                'insurance_expiration_date' => $row['insurance_expiration_date']? $this->formatDate($row['insurance_expiration_date']): null,
                'inspection_expiration_date'=> $row['inspection_expiration_date']? $this->formatDate($row['inspection_expiration_date']): null,
                'inspection_date'           => $row['inspection_date']? $this->formatDate($row['inspection_date']): null,

                'created_at'                => now(),
                'updated_at'                => now(),
            ];
        }

        foreach ($vehicleRows as $row) {
            Vehicle::create($row);
        }

        return $vehicleRows;
    }

    public function run(): void
    {
        DB::table('vehicles')->truncate();
        DB::table('vehicle_types')->truncate();
        DB::table('vehicle_companies')->truncate();

        $filePath = base_path('database/data/vehicles.csv');

        $handle = $this->openFile($filePath);

        $data = $this->readRowsAndTypes($handle);

        $typeMap = $this->createTypes(VehicleType::class, $data['vehicleTypes']);
        $companyMap = $this->createTypes(VehicleCompany::class,
            array_merge($data['vehicleOwners'], $data['vehicleUsers']));

        $vehicleRows = $this->createVehicles($data['rows'], $typeMap, $companyMap);

        $this->command->info(count($vehicleRows) . " vehicles imported successfully!");
    }
}
