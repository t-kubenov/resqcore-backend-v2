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
        $user = Auth::user();

        $query = Report::orderByRaw("is_closed, COALESCE(last_update_created_at, created_at) DESC");

        $query = $this->filterQuery($query, $request);

        if (!$user->hasRole('admin')) {
            $query->where('branch_office_id', $user->branch_id);
        }


        $reports = $query->with(['transport', 'updates.transport'])->paginate(); //todo: move logic to resources

        $reports->getCollection()->transform(function ($report) {
            $report->transport = $this->groupAndSumTransport($report);
            return $report;
        });

        return $reports;
    }

    public function getCountTable(Request $request)
    {
        $filteredQuery = $this->filterQuery(Report::query(), $request);

        $queryResults = (clone $filteredQuery)->selectRaw('emergency_type, branch_office_id, COUNT(*) as count')
            ->groupBy('emergency_type', 'branch_office_id')
            ->orderBy('emergency_type')
            ->orderBy('branch_office_id')
            ->get();

        $totalPerType = (clone $filteredQuery)->selectRaw('emergency_type, COUNT(*) as total_count')
            ->groupBy('emergency_type')
            ->pluck('total_count', 'emergency_type');

        $totalPerBranchOffice = (clone $filteredQuery)->selectRaw('branch_office_id, COUNT(*) as total_count')
            ->groupBy('branch_office_id')
            ->pluck('total_count', 'branch_office_id');

        $overallTotal = (clone $filteredQuery)->count();


        return [
            'data' => [
                'cells' => $queryResults,
                'total_per_type' => $totalPerType,
                'total_per_branch_office' => $totalPerBranchOffice,
                'overall_total' => $overallTotal,
            ]
        ];
    }

    public function export(Request $request)
    {
        $query = Report::orderByRaw("is_closed, COALESCE(last_update_created_at, created_at) DESC");

        $query = $this->filterQuery($query, $request);

        $reports = $query->with(['transport', 'updates.transport'])->paginate();

        $reports->getCollection()->transform(function ($report) {
            $report->transport = $this->groupAndSumTransport($report);
            return $report;
        });

        return $reports;
    }

    protected function groupAndSumTransport($report)
    {
        // Step 1: Merge transport from the report and its updates
        $updateTransports = $report->updates->flatMap(function ($update) {
            return $update->transport;
        });

        // Combine both report transport and update transports
        $mergedTransports = $report->transport->merge($updateTransports);

        // Step 2: Group transports by 'name' (or 'id') and sum their 'count'
        $groupedTransports = $mergedTransports->groupBy('name')->map(function ($group) {
            return [
                'id' => $group->first()->type,  // Keep the first id (or group by 'id' if necessary)
                'name' => $group->first()->name,  // Transport type name
                'count' => $group->sum('count')  // Sum all counts for this type
            ];
        })->values(); // Convert the grouped collection back to an array of values

        return $groupedTransports;
    }

    public function filterQuery($query, $request)
    {
        $query = $this->filterBranchOffice($query, $request);

        if (isset($request['emergency_type'])) {
            $query->whereIn('emergency_type', $request['emergency_type']);
        }

        if (isset($request['branch_office_id']) && auth()->user()->can('departure_view')) {
            $query->whereIn('branch_office_id', $request['branch_office_id']);
        }

        if (isset($request['is_emergency_line'])) {
            $query->where('is_emergency_line', $request['is_emergency_line']);
        }

        if (isset($request['date_start'])) {
            $query->where('call_date', '>=', $request['date_start']);
        }

        if (isset($request['date_end'])) {
            $query->where('call_date', '<=', $request['date_end']);
        }

        if (isset($request['is_closed'])) {
            $query->where('is_closed', $request['is_closed']);
        }

        if (isset($request['search'])) {
            $search = $request['search'];
            $query->where(function ($q) use ($search) {
                $q->where('line_facility_owner', 'ILIKE', "%{$search}%")
                    ->orWhere('emergency_location', 'ILIKE', "%{$search}%")
                    ->orWhereHas('facility_owner', function ($q) use ($search) {
                        $q->where('name', 'ILIKE', "%{$search}%");
                    });
            });
        }

        return $query;
    }


    private function filterBranchOffice($query, $request)
    {
        $user = Auth::user();

        if ($user->hasRole('branch_office_user')) {
            $query->where('branch_office_id', $user->branch_id);
        }
        return $query;
    }


    public function findReportWithDetails($id)
    {
        $report = Report::find($id);

        if (!$report) {
            return response()->json(['message' => __('reports.show.not-found')], 404);
        }

        $user = Auth::user();

        if ($user->hasRole('branch_office_user') && $user->branch_id !== $report->branch_office_id) {
            return response()->json(['message' => __('reports.show.not-allowed')], 403);
        }

        $report->load([
            'updates' => function ($query) {
                $query->with('transport')->orderBy('created_at');
            },
            'reporter:id,first_name,last_name,patronymic,email'
        ]);

        $report['emergency_type'] = $report->emergency;
        $report['reported_by'] = $report->reporter;

        $report = $this->countUpdateTrack($report);

        return new ReportResource($report);
    }

    private function countUpdateTrack($report) {
        $fields = [
            'evacuated_people_count' => [],
            'provided_medical_count' => [],
            'work_scope' => [],
            'situation_during_filing' => [],
            'client_equipment_count' => [],
            'third_parties_count' => [],
        ];

        $items = array_merge([$report], $report->updates->all() ?? []);

        foreach ($items as $item) {
            $fields['injuries'][] = [
                "time" => $item->created_at->format(config('app.date_format')),
                "minor_injury_count" => $item->minor_injury_count ?? 0,
                "severe_injury_count" => $item->severe_injury_count ?? 0,
                "fatal_injury_count" => $item->fatal_injury_count ?? 0
            ];
        }

        foreach ($items as $item) {
            $fields['transport'][] = [
                "time" => $item->created_at->format(config('app.date_format')),
                "data" => TransportResource::collection($item->transport)
            ];
        }

        $other_fields = [
            'evacuated_people_count',
            'provided_medical_count',
            'work_scope',
            'situation_during_filing',
            'client_equipment_count',
            'third_parties_count'
        ];

        foreach ($items as $item) {
            foreach ($other_fields as $field) {
                $fields[$field][] = [
                    "time" => $item->created_at->format(config('app.date_format')),
                    "data" => $item->{$field} ?? ($field === 'work_scope' || $field === 'situation_during_filing' ? null : 0)
                ];
            }
        }

// Update the report's track field and save
        $report->update_track = $fields;

        return $report;
    }

    public function createReport($attributes)
    {
        $user = Auth::user();

        $attributes['reported_by'] = $user->id; //todo:move to request?


        if (!$user->branch_id && !$user->hasRole('admin')) {
            return response()->json(['message' => __('reports.create.branch-id-required')], 422);
        }

        if ($user->hasRole('admin') && !$user->branch_id && !isset($attributes['branch_office_id'])) {
            return response()->json(['message' => __('reports.create.branch-id-required-admin')], 422); //todo: not ideal, need to fix sometime
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
