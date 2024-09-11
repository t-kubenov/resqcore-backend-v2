<?php

namespace App\Services;

use Illuminate\Http\Request;

use App\Models\Report;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ReportService
{
    public function getReports(Request $request)
    {
        $user = Auth::user();
        $query = Report::where('branch_office_id', $user->branch_id)->orderBy('call_date', 'desc');

        $validated = $this->validateRequest($request);

        if (isset($validated['is_emergency_line'])) {
            $query->where('is_emergency_line', $validated['is_emergency_line']);
        }

        if (isset($validated['date_start'])) {
            $query->where('call_date', '>=', $validated['date_start']);
        }

        if (isset($validated['date_end'])) {
            $query->where('call_date', '<=', $validated['date_end']);
        }

        return $query->paginate();
    }

    public function findReportWithDetails($id)
    {
        $report = Report::find($id);

        if ($report) {
            $report->load(['updates' => function ($query) {
                $query->with('transport')->orderBy('call_date');
            }]);
            $report->load('transport');

            return $report;
        }

        return null;

    }

    public function validateRequest(Request $request){
        $validated = $request->validate([
            'is_emergency_line' => 'sometimes|boolean',
            'date_start' => 'sometimes|date',
            'date_end' => 'sometimes|date',
        ]);

        if (isset($validated['date_start']) && isset($validated['date_end'])) {
            $request->validate([
                'date_start' => 'before_or_equal:date_end',
                'date_end' => 'after_or_equal:date_start',
            ]);
        }

        return $validated;
    }

    public function createReport($attributes)
    {
        $attributes['reported_by'] = Auth::user()->id;
        $attributes['branch_office_id'] = Auth::user()->branch_office_id ?? 1;

        $attributes['report_code'] = $this->generateReportCode($attributes['branch_office_id']);
        $attributes['emergency_type'] = $attributes['emergency_type']['id'];


        $report = Report::create($attributes);

        $this->checkReportForClosing($report);
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

    private function checkReportForClosing($report)
    {
        if ($report->close_date
            && $report->branch_worker_count > 0
            && $report->worker_man_hours > 0) {
            $report->is_closed = true;
            $report->save();
        }
    }
}
