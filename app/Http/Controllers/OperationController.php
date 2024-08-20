<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOperationRequest;
use App\Http\Requests\UpdateOperationRequest;
use App\Models\Operation;

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


    public function reports(Request $request)
    {
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
                'caller' => $departure->caller,
                'facility_name' => $departure->facility->location ?? $departure->line_facility_location,
                'emergency_location' => $departure->emergency_location ?? 'Н/Д',
                'call_date' => $departure->call_date,
                'emergency_name' => $departure->emergency_type, // Derive from emergency type
                'is_closed' => $departure->is_closed,
            ],
            ];
        }

        return response()->json(['data' => $data], 200);
    }

    public function showReport($id) {
        $report = Operation::find($id);

        if (!$report) {
            return response()->json(['error' => 'Report not found'], 404);
        }

        $data = [
            'id' => $report->id,
            'type' => 'report',
            'attributes' => [
                $report->toArray(),
            ],
        ];

        return response()->json(['data' => $data], 200);
    }

    public function storeReport(Request $request)
    {
        // $data = $request['data']['attributes'];

        $fields = $request->validate([
            'data.attributes.branch_office_id' => 'required',
            'data.attributes.facility_id' => 'required',
            'data.attributes.reported_by' => 'required',
            'data.attributes.report_code' => 'required',
            'data.attributes.caller' => 'required',
            'data.attributes.emergency_location' => 'required',
            'data.attributes.emergency_info' => 'required',
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

        // Flatten the validated data
        $attributes = $fields['data']['attributes'];

        // return response()->json(['data' => $attributes], 201); // 201 Created

        $report = Operation::create($attributes);

        $data = [];
        $data['id'] = $report->id;
        $data['type'] = 'report';
        $data['attributes'] = $report->toArray();

        return response()->json(['data' => $data], 201); // 201 Created
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
