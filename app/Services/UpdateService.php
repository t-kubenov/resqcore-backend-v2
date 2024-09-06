<?php

namespace App\Services;

use App\Models\Update;
use Illuminate\Support\Facades\Log;

class UpdateService extends ReportService
{
    public function createUpdate($report, $attributes)
    {
        $update = $report->updates()->create($attributes);

        if (isset($attributes['transport'])) {
            $this->parseTransport($update, $attributes['transport']);
        }

        return $update->load('transport');
    }

    private function parseTransport(Update $update, array $transport)
    {
        try {
            foreach ($transport as $vehicle) {
                $update->transport()->create([
                    'type' => $vehicle['id'],
                    'count' => $vehicle['count'],
                ]);
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
