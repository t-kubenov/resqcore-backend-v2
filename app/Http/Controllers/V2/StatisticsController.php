<?php

namespace App\Http\Controllers\V2;

use App\Http\Requests\V2\FilterReportRequest;
use App\Http\Services\V2\ReportService;
use App\Models\Report;
use App\Traits\Cacheable;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    use Cacheable;
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function getCountTable(Request $request)
    {
        $data = $this->cacheResponse('statistics:getCountTable', $request, function () use ($request) {
            $filteredQuery = $this->reportService->filterQuery(Report::query(), $request);

            return [
                'cells' => (clone $filteredQuery)->selectRaw('emergency_type, branch_office_id, COUNT(*) as count')
                    ->groupBy('emergency_type', 'branch_office_id')
                    ->orderBy('emergency_type')
                    ->orderBy('branch_office_id')
                    ->get(),
                'total_per_type' => (clone $filteredQuery)->selectRaw('emergency_type, COUNT(*) as total_count')
                    ->groupBy('emergency_type')
                    ->pluck('total_count', 'emergency_type'),
                'total_per_branch_office' => (clone $filteredQuery)->selectRaw('branch_office_id, COUNT(*) as total_count')
                    ->groupBy('branch_office_id')
                    ->pluck('total_count', 'branch_office_id'),
                'overall_total' => (clone $filteredQuery)->count(),
            ];
        });

        return response()->json(['data' => $data]);
    }

    public function getCardStatistics(FilterReportRequest $request)
    {
        $data = $this->cacheResponse('statistics:getCardStatistics', $request, function () use ($request) {
            $filterQuery = $this->reportService->filterQuery(Report::query(), $request);

            return [
                'total_reports' => (clone $filterQuery)->count(),
                'open_reports' => (clone $filterQuery)->where('is_closed', false)->count(),
                'closed_reports' => (clone $filterQuery)->count() - (clone $filterQuery)->where('is_closed', false)->count(),
                'worker_hours_total' => number_format((clone $filterQuery)->sum('worker_man_hours'), '2', '.', ''),
                'workers_total' => number_format((clone $filterQuery)->sum('branch_worker_count'), '2', '.', ''),
                'emergency_line_reports' => (clone $filterQuery)->where('is_emergency_line', true)->count(),
                'contract_reports' => (clone $filterQuery)->count() - (clone $filterQuery)->where('is_emergency_line', true)->count(),
            ];
        });

        return response()->json(['data' => $data]);
    }

    public function getEmergencyTypesDistribution(FilterReportRequest $request)
    {
        $data = $this->cacheResponse('statistics:getEmergencyTypesDistribution', $request, function () use ($request) {
            $filterQuery = $this->reportService->filterQuery(Report::selectRaw('emergency_type, count(id)')->with('emergency'), $request);

            return $filterQuery->groupBy('emergency_type')
                ->orderBy('emergency_type')
                ->get()
                ->map(function ($report) {
                    return [
                        'id' => $report->emergency_type,
                        'key' => $report->emergency->key ?? null,
                        'name' => $report->emergency->name ?? null,
                        'alias' => $report->emergency->alias ?? null,
                        'count' => $report->count,
                    ];
                });
        });

        return response()->json(['data' => $data], 200);
    }

    public function getEmergenciesPerBranchOffice(FilterReportRequest $request)
    {
        $data = $this->cacheResponse('statistics:getEmergenciesPerBranchOffice', $request, function () use ($request) {
            $filterQuery = $this->reportService->filterQuery(
                Report::selectRaw('branch_office_id, count(id)')->with('branch_office'),
                $request
            );

            return $filterQuery->groupBy('branch_office_id')
                ->orderBy('branch_office_id')
                ->get()
                ->map(function ($report) {
                    return [
                        'id' => $report->branch_office_id,
                        'key' => $report->branch_office->key ?? null,
                        'name' => $report->branch_office->name ?? null,
                        'alias' => $report->branch_office->alias ?? null,
                        'count' => $report->count,
                    ];
                });
        });

        return response()->json(['data' => $data], 200);
    }

    public function getInjuryCountsPerEmergencyType(FilterReportRequest $request)
    {
        $data = $this->cacheResponse('statistics:getInjuryCountsPerEmergencyType', $request, function () use ($request) {
            return $this->reportService->filterQuery(Report::query(), $request)
                ->with('emergency')
                ->select('emergency_type')
                ->selectRaw('SUM(minor_injury_total) as minor_injury_total')
                ->selectRaw('SUM(severe_injury_total) as severe_injury_total')
                ->selectRaw('SUM(fatal_injury_total) as fatal_injury_total')
                ->orderBy('emergency_type')
                ->groupBy('emergency_type')
                ->get()
                ->map(function ($report) {
                    return [
                        'emergency_type' => $report->emergency,
                        'minor_injury_total' => $report->minor_injury_total,
                        'severe_injury_total' => $report->severe_injury_total,
                        'fatal_injury_total' => $report->fatal_injury_total,
                    ];
                });
        });

        return response()->json(['data' => $data]);
    }

    public function getEvacuatedPeopleMedicalAid(FilterReportRequest $request)
    {
        $data = $this->cacheResponse('statistics:getEvacuatedPeopleMedicalAid', $request, function () use ($request) {
            $filteredQuery = $this->reportService->filterQuery(Report::query(), $request);

            return [
                'evacuated_people_total' => (clone $filteredQuery)->sum('evacuated_people_total'),
                'provided_medical_total' => (clone $filteredQuery)->sum('provided_medical_total'),
            ];
        });

        return response()->json(['data' => $data]);
    }


    public function getResponseTimePerEmergencyType(FilterReportRequest $request)
    {
        $data = $this->cacheResponse('statistics:getResponseTimePerEmergencyType', $request, function () use ($request) {
            return $this->reportService->filterQuery(Report::query(), $request)
                ->with('emergency')
                ->select('emergency_type')
                ->selectRaw('ROUND(AVG(EXTRACT(EPOCH FROM (work_start_date - call_date)) / 3600), 2) as avg_response_time')
                ->orderBy('emergency_type')
                ->groupBy('emergency_type')
                ->get()
                ->map(function ($report) {
                    return [
                        'emergency_type' => $report->emergency,
                        'avg_response_time' => $report->avg_response_time,
                    ];
                });
        });

        return response()->json(['data' => $data]);
    }


    public function getWorkerManHoursPerEmergency(FilterReportRequest $request)
    {
        $data = $this->cacheResponse('statistics:getWorkerManHoursPerEmergency', $request, function () use ($request) {
            return $this->reportService->filterQuery(Report::query(), $request)
                ->select('emergency_type')
                ->selectRaw('SUM(worker_man_hours) as man_hours')
                ->orderBy('emergency_type')
                ->groupBy('emergency_type')
                ->get()
                ->map(function ($report) {
                    return [
                        'emergency_type' => $report->emergency,
                        'worker_man_hours' => number_format($report->man_hours, '2', '.', ''),
                    ];
                });
        });

        return response()->json(['data' => $data]);
    }


    public function getTotalInjuriesImpactOverTime(FilterReportRequest $request)
    {
        $data = $this->cacheResponse('statistics:getTotalInjuriesImpactOverTime', $request, function () use ($request) {
            return $this->reportService->filterQuery(Report::query(), $request)
                ->select('call_date')
                ->selectRaw('SUM(SUM(minor_injury_total)) OVER (ORDER BY call_date) as cumulative_minor_injury_total')
                ->selectRaw('SUM(SUM(severe_injury_total)) OVER (ORDER BY call_date) as cumulative_severe_injury_total')
                ->selectRaw('SUM(SUM(fatal_injury_total)) OVER (ORDER BY call_date) as cumulative_fatal_injury_total')
                ->selectRaw('SUM(SUM(evacuated_people_total)) OVER (ORDER BY call_date) as cumulative_evacuated_people_total')
                ->selectRaw('SUM(SUM(provided_medical_total)) OVER (ORDER BY call_date) as cumulative_provided_medical_total')
                ->groupBy('call_date')
                ->orderBy('call_date')
                ->get();
        });

        return response()->json(['data' => $data]);
    }


    public function getTransportUsage(FilterReportRequest $request)
    {
        $data = $this->cacheResponse('statistics:getTransportUsage', $request, function () use ($request) {
            $filterQuery = $this->reportService->filterQuery(Report::query(), $request);

            return $filterQuery->join('transports', 'reports.id', '=', 'transports.report_id')
                ->join('transport_types', 'transports.type', '=', 'transport_types.id')
                ->selectRaw('transports.type, transport_types.name as name, transport_types.alias as alias, SUM(transports.count) as total_count')
                ->groupBy('transports.type', 'name', 'alias')
                ->orderBy('type')
                ->get();
        });

        return response()->json(['data' => $data], 200);
    }
}
