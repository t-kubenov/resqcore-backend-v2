<?php

namespace App\Http\Services\V2;

use App\Models\Update;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UpdateService extends ReportService
{
    public function createUpdate($report, $attributes)
    {
        $user = Auth::user();

        $attributes['reported_by'] = $user->id;

        $update = $report->updates()->create($attributes);

        $this->checkUpdateForClosing($update);
        $this->calculateTotalsAndUpdateTimestamp($update);

        if (isset($attributes['transport'])) {
            $this->parseTransport($update, $attributes['transport']);
            $update->load('transport');
        }

        return $update;
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

    private function calculateTotalsAndUpdateTimestamp(Update $update)
    {
        $report = $update->report;

        $report->minor_injury_total += $update->minor_injury_count;
        $report->severe_injury_total += $update->severe_injury_count;
        $report->fatal_injury_total += $update->fatal_injury_count;
        $report->evacuated_people_total += $update->evacuated_people_count;
        $report->provided_medical_total += $update->provided_medical_count;
        $report->client_equipment_total += $update->client_equipment_count;
        $report->third_parties_total += $update->third_parties_count;
        $report->branch_worker_total += $update->branch_worker_count;

        $report->last_update_created_at = $update->created_at;

        $report->save();
    }

    private function checkUpdateForClosing($update)
    {
        $report = $update->report;

        if ($update->close_date &&
            $update->worker_man_hours > 0
        ){
            $report->close_date = $update->close_date;
            $report->worker_man_hours = $update->worker_man_hours;
            $report->is_closed = true;
            $report->save();
        }
    }
}
