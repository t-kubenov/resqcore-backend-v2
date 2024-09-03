<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOperationRequest;
use App\Http\Requests\UpdateOperationRequest;
use App\Models\Operation;

use App\Http\Resources\ReportResource;

use Illuminate\Http\Request;

class OperationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function departures(Request $request) {
        $page = $request->input('page');
        $branchOffice = $request->input('branch_office');
        $isEmergencyLine = $request->input('is_emergency_line');
        $dateFilter = $request->input('date_filter');

        $query = Operation::query();

        if ($page) {
            $query->where('page', $page);
        }

        if ($branchOffice) {
            $query->where('branch_office', $branchOffice);
        }

        if ($isEmergencyLine !== null) {
            $query->where('is_emergency_line', $isEmergencyLine);
        }

        if ($dateFilter) {
            $dates = explode(',', $dateFilter);
            $startDate = $dates[0];
            $endDate = $dates[1];

            $query->whereBetween('call_date', [$startDate, $endDate]);
        }

        $reports = $query->orderBy('call_date', 'desc')->get();

        $data = [];
        foreach ($reports as $departure) {
            $data[] = [
            'id' => $departure->id,
            'type' => 'report',
            'attributes' => [
                'report_code' => $departure->report_code,
                'work_start_date' => $departure->work_start_date,
                'close_date' => $departure->close_date,
                'facility_owner' => $departure->facility->owner_->name ?? $departure->line_facility_owner,
                'emergency_name' => $departure->emergency_type, // Derive from emergency type
                'emergency_location' => $departure->emergency_location,
                'branch_worker_count' => $departure->branch_worker_count,
                'worker_man_hours' => $departure->worker_man_hours,
                'branch_office_id' => $departure->branch_office_id,
                ],
            'relationships' => [
                    'facility' => $departure->facility,
                    'emergency_name' => $departure->emergency_name,
                    'branch_office' => $departure->branch_office->name,
                ],
            ];
        }

        return response()->json(['data' => $data], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOperationRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Operation $operation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOperationRequest $request, Operation $operation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Operation $operation)
    {
        //
    }
}
