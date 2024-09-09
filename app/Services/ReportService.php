<?php

namespace App\Services;

use App\Models\Report;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

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

            return $report;
        }

        return null;

    }

    public function createReport($attributes)
    {
        $attributes['reported_by'] = Auth::user()->id;
        $attributes['branch_office_id'] = Auth::user()->branch_office_id ?? 1;

        $attributes['report_code'] = $this->generateReportCode($attributes['branch_office_id']);
        $attributes['emergency_type'] = $attributes['emergency_type']['id'];


        $report = Report::create($attributes);
        // $this->checkReportForClosing($report);
        $this->calculateTotals($report);

        if (isset($attributes['transport'])) {
            $this->parseTransport($report, $attributes['transport']);
            $report->load('transport');
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

    private function calculateTotals($report)
    {
        $report->minor_injury_total = $report->minor_injury_count;
        $report->severe_injury_total = $report->severe_injury_count;
        $report->fatal_injury_total = $report->fatal_injury_count;
        $report->evacuated_people_total = $report->evacuated_people_count;
        $report->provided_medical_total = $report->provided_medical_count;
        $report->client_equipment_total = $report->client_equipment_count;
        $report->third_parties_total = $report->third_parties_count;

        $report->save();
    }

    // private function checkReportForClosing($report)
    // {
    //     if ($report->is_closed) {
    //         return;
    //     }

    //     $fields = [
    //         'branch_worker_count',
    //         'worker_man_hours',
    //         'close_date',
    //     ];

    //     $field_count = 0;

    //     foreach ($fields as $field) {
    //         if ($report->$field) {
    //             $field_count++;
    //             continue;
    //         }
    //         break;
    //     }

    //     if ($field_count === 0) {
    //         return;
    //     }
    //     elseif ($field_count === count($fields)) {
    //         $report->is_closed = false;
    //         $report->save();
    //         return;
    //     }
    //     else {
    //         return;
    //     }

    //     // $report->is_closed = true;
    //     // $report->save();
    // }
}
