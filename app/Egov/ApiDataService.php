<?php

namespace App\Egov;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class ApiDataService
{
    public function fetchData($uri)
    {
        $client = new Client();

        try {
            $response = $client->request('GET', $uri);

            if ($response->getStatusCode() >= 400) {
                Log::error('HTTP error in ApiDataService: ' . $response->getStatusCode());
                return [];
            }

            $data = json_decode($response->getBody(), true);

        } catch (\Exception $e) {
            Log::error('Error in ApiDataService: ' . $e->getMessage());
            $data = [];
        }

        return $data;

    }
}
