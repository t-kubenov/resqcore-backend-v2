<?php

namespace App\Services;

use App\Models\Report;
use App\Http\Resources\DepartureResource;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class DepartureService
{
    public function getDepartures(Request $request)
    {
        $validated = $this->validateRequest($request);

        $query = Report::orderByRaw("is_closed, COALESCE(last_update_created_at, created_at) DESC");

        $query = $this->filterQuery($query, $validated);

        if (isset($validated['no_pagination']) && $validated['no_pagination']) {
            $reports = $query->with(['transport', 'updates.transport'])->get();

            // Merge the updates' transports into the parent report's transport object
            $reports->transform(function ($report) {
                // Call the new group and sum function for transport
                $report->transport = $this->groupAndSumTransport($report);
                return $report;
            });
        }

        else {
            $reports = $query->with(['transport', 'updates.transport'])->paginate();

            $reports->getCollection()->transform(function ($report) {
                $report->transport = $this->groupAndSumTransport($report);
                return $report;
            });
        }

        return $reports;
    }

    public function getCountTable(Request $request)
    {
        $validated = $this->validateRequest($request);

        $filteredQuery = $this->filterQuery(Report::query(), $validated);

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

    public function filterQuery($query, $validated)
    {
        if (isset($validated['emergency_type'])) {
            $query->where('emergency_type', $validated['emergency_type']);
        }

        if (isset($validated['branch_office_id'])) {
            $query->where('branch_office_id', $validated['branch_office_id']);
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

        if (isset($validated['search'])) {
            $search = $validated['search'];
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


    public function validateRequest(Request $request){ //todo: create separate validation class
        $validated = $request->validate([
            'emergency_type' => 'sometimes|integer',
            'branch_office_id' => 'sometimes|integer',
            'is_emergency_line' => 'sometimes|boolean',
            'date_start' => 'sometimes|date',
            'date_end' => 'sometimes|date',
            'search' => 'sometimes|string|max:255', // Add search parameter
            'no_pagination' => 'sometimes|boolean', // Only for all departures
        ]);

        if (isset($validated['date_start']) && isset($validated['date_end'])) {
            $request->validate([
                'date_start' => 'before_or_equal:date_end',
                'date_end' => 'after_or_equal:date_start',
            ]);
        }

        return $validated;
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
}
