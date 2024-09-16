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

        return $query->paginate();
    }


    public function validateRequest(Request $request){ //todo: create separate validation class
        $validated = $request->validate([
            'branch_office_id' => 'sometimes|integer',
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

    public function canViewDepartures($user)
    {
        if ($user->can('departure_view')) {
            return true;
        }

        return false;
    }

}
