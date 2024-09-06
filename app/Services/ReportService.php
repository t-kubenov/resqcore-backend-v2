<?php

namespace App\Services;

use App\Models\Report;
use Illuminate\Support\Facades\Log;

class ReportService
{
    public function paginateReports()
    {
        return Report::paginate();
    }

    public function findReportWithDetails($id, $includeUpdates, $includeTransport)
    {
        $report = Report::find($id);

        if ($report) {
            if ($includeUpdates) {
                $report->load('updates');
            }
            if ($includeTransport) {
                $report->load('transport');
            }
        }

        return $report;
    }

    public function createReport($attributes)
    {
        $attributes['report_code'] = $this->generateReportCode($attributes['branch_office_id']);
        $attributes['emergency_type'] = $attributes['emergency_type']['id'];

        $report = Report::create($attributes);

        if (isset($attributes['transport'])) {
            $this->parseTransport($report, $attributes['transport']);
        }

        return $report;
    }

    public function deleteReport($id)
    {
        $report = Report::find($id);
        if ($report) {
            $report->delete();
            return true;
        }
        return false;
    }

    private function generateReportCode($branchOfficeId)
    {
        $reportCount = Report::where('branch_office_id', $branchOfficeId)->count() + 1;
        return $branchOfficeId . ' - ' . $reportCount;
    }

    private function parseTransport(Report $report, array $transport)
    {
        try {
            foreach ($transport as $vehicle) {
                $report->transport()->create([
                    'type' => $vehicle['id'],
                    'count' => $vehicle['count'],
                ]);
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }

    private function getReportTransport($report)
    {
        return $report->transport->map(function ($vehicle) {
            return [
                'id' => $vehicle->type,
                'name' => $vehicle->type->name,
                'count' => $vehicle->count,
            ];
        });
    }
}
