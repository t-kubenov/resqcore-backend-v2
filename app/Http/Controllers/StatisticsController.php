<?php

namespace App\Http\Controllers;

use App\Models\BranchOffice;
use App\Models\EmergencyType;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    public function getCardStatistics(Request $request)
    {
        $validated = $this->validateRequest($request);

        $filterQuery = $this->filterQuery(Report::query(), $validated);

        $totalReports = (clone $filterQuery)->count();
        $openReports = (clone $filterQuery)->where('is_closed', false)->count();
        $closedReports = $totalReports - $openReports;
        $workerHoursTotal = (clone $filterQuery)->sum('worker_man_hours');
        $workersTotal = (clone $filterQuery)->sum('branch_worker_count');
        $emergencyLineReports = (clone $filterQuery)->where('is_emergency_line', 'true')->count();
        $contractReports = $totalReports - $emergencyLineReports;

        return response()->json([
            'data' => [
                'total_reports' => $totalReports,
                'open_reports' => $openReports,
                'closed_reports' => $closedReports,
                'worker_hours_total' => number_format((float)$workerHoursTotal, 2, '.', ''),
                'workers_total' => $workersTotal,
                'emergency_line_reports' => $emergencyLineReports,
                'contract_reports' => $contractReports,
            ]
        ]);
    }

    public function getEmergencyTypesDistribution(Request $request)
    {
        $validated = $this->validateRequest($request);

        $filterQuery = $this->filterQuery(Report::selectRaw('emergency_type, count(id)')->with('emergency'), $validated);

        $emergencyTypes = $filterQuery->groupBy('emergency_type')->orderBy('emergency_type')->get();

        $data = $emergencyTypes->map(function ($report) {
            return [
                'id' => $report->emergency_type,
                'key' => $report->emergency->key ?? null,
                'name' => $report->emergency->name ?? null,
                'count' => $report->count,
            ];
        });

        return response()->json(['data' => $data], 200);
    }

    public function getEmergenciesPerBranchOffice(Request $request)
    {
        $validated = $this->validateRequest($request);

        $filterQuery = $this->filterQuery(Report::selectRaw('branch_office_id, count(id)')->with('branch_office'), $validated);

        $branchOffices = $filterQuery->groupBy('branch_office_id')->orderBy('branch_office_id')->get();

        $data = $branchOffices->map(function ($report) {
            return [
                'id' => $report->branch_office_id,
                'key' => $report->branch_office->key ?? null,
                'name' => $report->branch_office->name ?? null,
                'alias' => $report->branch_office->alias ?? null,
                'count' => $report->count,
            ];
        });

        return response()->json(['data' => $data], 200);
    }

    public function getInjuryCountsPerEmergencyType(Request $request)
    {
        $validated = $this->validateRequest($request);
        // list of emergency types and the number of injuries(minor, severe, fatal) for each type

        $injuryCounts = $this->filterQuery(Report::query(), $validated)->with('emergency') // Load the emergency type relationship
            ->select('emergency_type') // Assuming emergency_type_id is the foreign key
            ->selectRaw('SUM(minor_injury_total) as minor_injury_total')
            ->selectRaw('SUM(severe_injury_total) as severe_injury_total')
            ->selectRaw('SUM(fatal_injury_total) as fatal_injury_total')
            ->orderBy('emergency_type')
            ->groupBy('emergency_type')
            ->get()
            ->map(function ($report) {
                return [
                    'emergency_type' => $report->emergency, // Access related data
                    'minor_injury_total' => $report->minor_injury_total,
                    'severe_injury_total' => $report->severe_injury_total,
                    'fatal_injury_total' => $report->fatal_injury_total,
                ];
            });

        return response()->json(['data' => $injuryCounts]);
    }

    public function getEvacuatedPeopleMedicalAid(Request $request)
    {
        $validated = $this->validateRequest($request);

        $filteredQuery = $this->filterQuery(Report::query(), $validated);

        $evacuated_people_total = (clone $filteredQuery)->sum('evacuated_people_total');
        $provided_medical_total = (clone $filteredQuery)->sum('provided_medical_total');

        return response()->json(['data' => [
            'evacuated_people_total' => $evacuated_people_total,
            'provided_medical_total' => $provided_medical_total,
            ]
        ]);

    }

    public function getResponseTimePerEmergencyType(Request $request)
    {
        $validated = $this->validateRequest($request);

        $responseTimes = $this->filterQuery(Report::query(), $validated)->with('emergency') // Load the emergency type relationship
            ->select('emergency_type') // Assuming emergency_type_id is the foreign key
            ->selectRaw('ROUND(AVG(EXTRACT(EPOCH FROM (work_start_date - call_date)) / 3600), 2) as avg_response_time')
            ->orderBy('emergency_type')
            ->groupBy('emergency_type')
            ->get()
            ->map(function ($report) {
                return [
                    'emergency_type' => $report->emergency, // Access related data
                    'avg_response_time' => $report->avg_response_time,
                ];
            });

        return response()->json(['data' => $responseTimes]);
    }

    public function getWorkerManHoursPerEmergency(Request $request)
    {
        $validated = $this->validateRequest($request);

        $workerManHours = $this->filterQuery(Report::query(), $validated)->select('emergency_type')
            ->selectRaw('SUM(worker_man_hours) as man_hours')
            ->orderBy('emergency_type')
            ->groupBy('emergency_type')
            ->get()
            ->map(function ($report) {
                return [
                    'emergency_type' => $report->emergency, // Access related data
                    'worker_man_hours' => number_format($report->man_hours, '2', '.', ''),
                ];
            });

        return response()->json(['data' => $workerManHours]);
    }

    public function getTotalInjuriesImpactOverTime(Request $request)
    {
        // This cumulative line chart tracks the overall impact of emergencies, including injuries, evacuations, and medical assistance provided, over time.
        $validated = $this->validateRequest($request);

        $totalInjuries = $this->filterQuery(Report::query(), $validated)->select('call_date')
            ->selectRaw('SUM(SUM(minor_injury_total)) OVER (ORDER BY call_date) as cumulative_minor_injury_total')
            ->selectRaw('SUM(SUM(severe_injury_total)) OVER (ORDER BY call_date) as cumulative_severe_injury_total')
            ->selectRaw('SUM(SUM(fatal_injury_total)) OVER (ORDER BY call_date) as cumulative_fatal_injury_total')
            ->selectRaw('SUM(SUM(evacuated_people_total)) OVER (ORDER BY call_date) as cumulative_evacuated_people_total')
            ->selectRaw('SUM(SUM(provided_medical_total)) OVER (ORDER BY call_date) as cumulative_provided_medical_total')
            ->groupBy('call_date')
            ->orderBy('call_date')
            ->get();
        return response()->json(['data' => $totalInjuries]);
    }

    public function filterQuery($query, $validated) // todo: this is a truplicate at this point, create a separate validation class pls
    {
        if (isset($validated['emergency_type'])) {
            $query->whereIn('emergency_type', $validated['emergency_type']);
        }

        if (isset($validated['branch_office_id'])) {
            $query->whereIn('branch_office_id', $validated['branch_office_id']);
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

        return $query;
    }


    public function validateRequest(Request $request){ //todo: create separate validation class, and not reuse code like this ;-;
        $validated = $request->validate([
            'emergency_type' => 'sometimes|array|min:1',
            'emergency_type.*' => 'integer',
            'branch_office_id' => 'sometimes|array|min:1',
            'branch_office_id.*' => 'integer',
            'is_emergency_line' => 'sometimes|boolean',
            'date_start' => 'sometimes|date',
            'date_end' => 'sometimes|date',
//            'search' => 'sometimes|string|max:255', // Add search parameter
        ]);

        if (isset($validated['date_start']) && isset($validated['date_end'])) {
            $request->validate([
                'date_start' => 'before_or_equal:date_end',
                'date_end' => 'after_or_equal:date_start',
            ]);
        }

        return $validated;
    }
}
