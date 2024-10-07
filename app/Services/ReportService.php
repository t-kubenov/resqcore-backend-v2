<?php

namespace App\Services;

use App\Http\Resources\TransportResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\Models\Report;
use App\Http\Resources\ReportResource;

class ReportService
{
    public function getReports(Request $request)
    {
        $validated = $this->validateRequest($request);

        $user = Auth::user();

        $query = Report::orderBy('call_date', 'desc');


        if (!$user->hasRole('admin')) {
            $query->where('branch_office_id', $user->branch_id);
        }

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

        if (!$report) {
            return response()->json(['message' => 'Report not found'], 404);
        }

        $user = Auth::user();

        if (!($report->branch_office_id == $user->branch_id || $user->hasRole('admin'))) {
            return response()->json(['message' => 'Report does not belong to your branch.'], 403);
        }

        $report->load(['updates' => function ($query) {
            $query->with('transport');
        }])->orderBy('created_at');

        $report->load('transport');

        $report = $this->countUpdateTrack($report);

        return new ReportResource($report);
    }

    private function countUpdateTrack($report) {
        $fields = [
            'minor_injury_count' => [],
            'severe_injury_count' => [],
            'fatal_injury_count' => [],
            'evacuated_people_count' => [],
            'provided_medical_count' => [],
            'work_scope' => [],
            'situation_during_filing' => [],
            'client_equipment_count' => [],
            'third_parties_count' => [],
            'transport' => [],
        ];

        $items = array_merge([$report], $report->updates->all() ?? []);

        // Loop through each report item and store the field data with timestamp
        foreach ($items as $index => $item) {
            foreach ($fields as $field => &$values) {
                $values[] = [
                    "time" => $item->created_at->format('Y-m-d H:i'),
                    "data" => $field === 'transport'
                        ? TransportResource::collection($item->transport)
                        : $item->{$field} ?? null
                ];
            }
        }

        // Update the report's track field and save
        $report->update_track = $fields;

        return $report;
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
        $user = Auth::user();

        $attributes['reported_by'] = $user->id;


        if (!$user->branch_id && !$user->hasRole('admin')) {
            return response()->json(['message' => 'Branch office ID is required.'], 422);
        }

        if ($user->hasRole('admin') && !$user->branch_id && !isset($attributes['branch_office_id'])) {
            return response()->json(['message' => 'Branch office ID is required for admins without a branch.'], 422);
        }

        $attributes['branch_office_id'] = $user->branch_id ?? $attributes['branch_office_id']; //todo: move this to separate function

        $attributes['report_code'] = $this->generateReportCode($attributes['branch_office_id']);
        $attributes['emergency_type'] = $attributes['emergency_type']['id'];


        $report = Report::create($attributes);

        $this->checkReportForClosing($report);
        $this->calculateTotals($report);

        if (isset($attributes['transport'])) {
            $this->parseTransport($report, $attributes['transport']);
            $report->load('transport');
        }

        return new ReportResource($report);
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

    private function validateBranchId (){
        //
    }

    private function generateReportCode($branchOfficeId)
    {
        $reportCount = Report::where('branch_office_id', $branchOfficeId)->count() + 1;
        return $branchOfficeId . '-' . $reportCount;
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
