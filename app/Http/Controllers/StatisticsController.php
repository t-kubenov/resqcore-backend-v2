<?php

namespace App\Http\Controllers;

use App\Models\BranchOffice;
use App\Models\EmergencyType;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    public function getEmergencyTypesDistribution()
    {
        $emergencyTypes = EmergencyType::withCount('reports')->get();

        return response()->json(['data' => $emergencyTypes], 200);
    }

    public function getEmergenciesPerBranchOffice()
    {
        $branchOffices = BranchOffice::withCount('reports')->get();

        return response()->json(['data' => $branchOffices], 200);
    }

    public function getInjuryCountsPerEmergencyType()
    {
        // list of emergency types and the number of injuries(minor, severe, fatal) for each type

        $injuryCounts = Report::with('emergency') // Load the emergency type relationship
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

    public function getEvacuatedPeopleMedicalAid()
    {
        $evacuated_people_total = Report::sum('evacuated_people_total');
        $provided_medical_total = Report::sum('provided_medical_total');

        return response()->json(['data' => [
            'evacuated_people_total' => $evacuated_people_total,
            'provided_medical_total' => $provided_medical_total,
            ]
        ]);

    }

    public function getResponseTimePerEmergencyType()
    {
        $responseTimes = Report::with('emergency') // Load the emergency type relationship
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

    public function getWorkerManHoursPerEmergency()
    {
        $workerManHours = Report::select('emergency_type')
            ->selectRaw('SUM(worker_man_hours) as man_hours')
            ->orderBy('emergency_type')
            ->groupBy('emergency_type')
            ->get()
            ->map(function ($report) {
                return [
                    'emergency_type' => $report->emergency, // Access related data
                    'worker_man_hours' => $report->man_hours,
                ];
            });

        return response()->json(['data' => $workerManHours]);
    }

    public function getTotalInjuriesImpactOverTime()
    {
        // This cumulative line chart tracks the overall impact of emergencies, including injuries, evacuations, and medical assistance provided, over time.

        $totalInjuries = Report::select('call_date')
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
}
