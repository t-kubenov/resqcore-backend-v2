<?php

namespace App\Http\Controllers;

use App\Http\Requests\FilterReportRequest;
use App\Models\BranchOffice;
use App\Models\EmergencyType;
use App\Models\Report;
use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function getCardStatistics(FilterReportRequest $request)
    {
        $filterQuery = $this->reportService->filterQuery(Report::query(), $request);

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

    public function getEmergencyTypesDistribution(FilterReportRequest $request)
    {
        $filterQuery = $this->reportService->filterQuery(Report::selectRaw('emergency_type, count(id)')->with('emergency'), $request);

        $emergencyTypes = $filterQuery->groupBy('emergency_type')->orderBy('emergency_type')->get();

        $data = $emergencyTypes->map(function ($report) {
            return [
                'id' => $report->emergency_type,
                'key' => $report->emergency->key ?? null,
                'name' => $report->emergency->name ?? null,
                'alias' => $report->emergency->alias ?? null,
                'count' => $report->count,
            ];
        });

        return response()->json(['data' => $data], 200);
    }

    public function getEmergenciesPerBranchOffice(FilterReportRequest $request)
    {
        $filterQuery = $this->reportService->filterQuery(Report::selectRaw('branch_office_id, count(id)')->with('branch_office'), $request);

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

    public function getInjuryCountsPerEmergencyType(FilterReportRequest $request)
    {
        // list of emergency types and the number of injuries(minor, severe, fatal) for each type

        $injuryCounts = $this->reportService->filterQuery(Report::query(), $request)->with('emergency') // Load the emergency type relationship
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

    public function getEvacuatedPeopleMedicalAid(FilterReportRequest $request)
    {
        $filteredQuery = $this->reportService->filterQuery(Report::query(), $request);

        $evacuated_people_total = (clone $filteredQuery)->sum('evacuated_people_total');
        $provided_medical_total = (clone $filteredQuery)->sum('provided_medical_total');

        return response()->json(['data' => [
            'evacuated_people_total' => $evacuated_people_total,
            'provided_medical_total' => $provided_medical_total,
            ]
        ]);

    }

    public function getResponseTimePerEmergencyType(FilterReportRequest $request)
    {
        $responseTimes = $this->reportService->filterQuery(Report::query(), $request)->with('emergency') // Load the emergency type relationship
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

    public function getWorkerManHoursPerEmergency(FilterReportRequest $request)
    {
        $workerManHours = $this->reportService->filterQuery(Report::query(), $request)->select('emergency_type')
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

    public function getTotalInjuriesImpactOverTime(FilterReportRequest $request)
    {
        // This cumulative line chart tracks the overall impact of emergencies, including injuries, evacuations, and medical assistance provided, over time.

        $totalInjuries = $this->reportService->filterQuery(Report::query(), $request)->select('call_date')
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

    public function getTransportUsage(FilterReportRequest $request)
    {
        $filterQuery = $this->reportService->filterQuery(Report::query(), $request);

        $data = $filterQuery->join('transports', 'reports.id', '=', 'transports.report_id')
            ->join('transport_types', 'transports.type', '=', 'transport_types.id')
            ->selectRaw('transports.type, transport_types.name as name, transport_types.alias as alias, SUM(transports.count) as total_count')
            ->groupBy('transports.type', 'name', 'alias')
            ->orderBy('type')
            ->get();

        return response()->json(['data' => $data], 200);

    }
}
