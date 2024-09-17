<?php

namespace App\Services;

use App\Models\Report;
use App\Http\Resources\DepartureResource;
use App\Services\DepartureService;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class DepartureService
{
    public function getDepartures(Request $request)
    {
        $validated = $this->validateRequest($request);

        $query = Report::orderBy('call_date', 'desc');

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

        return $query->paginate();
    }

    public function getCountTable(Request $request)
    {
        $query = Report::selectRaw('emergency_type, branch_office_id, COUNT(*) as count')
            ->groupBy('emergency_type', 'branch_office_id')
            ->orderBy('emergency_type')
            ->orderBy('branch_office_id')
            ->get();

        // Calculate the total count for each emergency type
        $totalPerType = Report::selectRaw('emergency_type, COUNT(*) as total_count')
            ->groupBy('emergency_type')
            ->pluck('total_count', 'emergency_type');

        // Calculate the total count for each branch office
        $totalPerBranchOffice = Report::selectRaw('branch_office_id, COUNT(*) as total_count')
            ->groupBy('branch_office_id')
            ->pluck('total_count', 'branch_office_id');

        // Calculate the overall total count
        $overallTotal = Report::count();

        return [
            'data' => [
                'cells' => $query,
                'total_per_type' => $totalPerType,
                'total_per_branch_office' => $totalPerBranchOffice,
                'overall_total' => $overallTotal,
            ]
        ];
    }


    public function validateRequest(Request $request){ //todo: create separate validation class
        $validated = $request->validate([
            'branch_office_id' => 'sometimes|integer',
            'is_emergency_line' => 'sometimes|boolean',
            'date_start' => 'sometimes|date',
            'date_end' => 'sometimes|date',
            'search' => 'sometimes|string|max:255', // Add search parameter
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
