<?php

namespace Database\Seeders\Egov;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

use App\Models\Egov\SubsoilContract;
use App\Services\ApiDataService;

class SubsoilContractSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SubsoilContract::truncate();

        $apiDataService = new ApiDataService();

        $data = $apiDataService->fetchData(
            'https://data.egov.kz/api/v4/zher_koinauyn_paidalanushylar_/v6?apiKey='.config('services.egov.api_key').'&source={"size":1000000}'
        );

        foreach ($data as $item){
            SubsoilContract::create([
                'id' => $item['id'],
                'name' => $item['nedropolzovatel_ru'],
            ]);
        }
    }
}
