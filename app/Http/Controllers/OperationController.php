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

    public function storeReport(Request $request)
    {
        // $data = $request['data']['attributes'];

        $fields = $request->validate([
            'data.attributes.branch_office_id' => 'required|integer',
            'data.attributes.facility_id' => 'nullable|integer',
            'data.attributes.reported_by' => 'required',
            // 'data.attributes.report_code' => 'required',
            'data.attributes.caller' => 'required',
            'data.attributes.emergency_location' => 'required',
            'data.attributes.emergency_info' => 'nullable|string',
            'data.attributes.call_date' => 'required|date',
            'data.attributes.work_start_date' => 'required|date',
            'data.attributes.emergency_type' => 'required|integer',
            'data.attributes.is_emergency_line' => 'required|boolean',
            'data.attributes.close_date' => 'nullable|date',
            'data.attributes.is_closed' => 'required|boolean',
            'data.attributes.minor_injury_count' => 'nullable|integer',
            'data.attributes.severe_injury_count' => 'nullable|integer',
            'data.attributes.fatal_injury_count' => 'nullable|integer',
            'data.attributes.evacuated_people_count' => 'nullable|integer',
            'data.attributes.provided_medical_count' => 'nullable|integer',
            'data.attributes.client_equipment_count' => 'nullable|integer',
            'data.attributes.third_parties_count' => 'nullable|integer',
            'data.attributes.branch_worker_count' => 'nullable|integer',
            'data.attributes.worker_man_hours' => 'nullable|numeric',
            'data.attributes.work_scope' => 'required',
            'data.attributes.situation_during_filing' => 'nullable',
            'data.attributes.other_info' => 'nullable',
        ]);


        $notNullableVariables = [
            'is_closed',
            'is_emergency_line',
            'branch_office_id',
            'reported_by',
            'report_code',
            'caller',
            'call_date',
            'work_start_date',
            'emergency_type',
            'work_scope'
        ];

        // Flatten the validated data
        $attributes = $fields['data']['attributes'];

        // report code is branch office id and the order of the report from that branch office
        $attributes['report_code'] = $attributes['branch_office_id'] . ' - ' . Operation::where('branch_office_id', $attributes['branch_office_id'])->count()+1;

        $report = Operation::create($attributes);

        $data = [];
        $data['id'] = $report->id;
        $data['type'] = 'report';
        $data['attributes'] = $report->toArray();

        return response()->json(['data' => $data], 201); // 201 Created
    }

    public function updateReport(Request $request, $id)
    {
        $report = Operation::find($id);

        if (!$report) {
            return response()->json(['error' => 'Report not found'], 404);
        }

        // tbc
    }

    public function deleteReport($id)
    {
        $report = Operation::find($id);

        if (!$report) {
            return response()->json(['error' => 'Report not found'], 404);
        }

        $report->delete();

        return response()->json(null, 204); // 204 No Content
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
                'branch_office_name' => $departure->branch_office_id,
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
