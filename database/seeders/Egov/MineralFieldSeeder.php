<?php

namespace Database\Seeders\Egov;

use App\Egov\ApiDataService;
use App\Egov\MineralField;
use Illuminate\Database\Seeder;

class MineralFieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MineralField::truncate();

        $apiDataService = new ApiDataService();

        $data = $apiDataService->fetchData(
            'https://data.egov.kz/api/v4/stat_kgn_118/v7?apiKey='.config('services.egov.api_key').'&source={"size":1000000}'
        );

        foreach ($data as $item){
            MineralField::create([
                'id' => $item['<название_поля_1>'],
                'name' => $item['<название_поля_2>'],
            ]);
        }
    }
}
