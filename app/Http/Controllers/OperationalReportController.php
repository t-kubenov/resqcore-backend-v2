<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOperationalReportRequest;
use App\Http\Requests\UpdateOperationalReportRequest;
use App\Models\OperationalReport;

class OperationalReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $operationalReports = OperationalReports::all();

        $data = [];
        foreach ($operationalReports as $departure) {
            $data[] = [
                'id' => $departure->id,
                'type' => 'report',
                'attributes' => [
                    'report_code' => $departure->report_code,
                    'caller' => $departure->caller,
                    'facility_name' => $departure->facility->location?? $departure->line_facility_location,
                    'call_date' => $departure->call_date->toIso8601String(),
                    'emergency_name' => $departure->emergency_type, // Derive from energency type
                    'is_closed' => $departure->is_closed,
                ],
            ];
        }

        return response()->json(['data' => $data]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOperationalReportRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(OperationalReport $operationalReport)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOperationalReportRequest $request, OperationalReport $operationalReport)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OperationalReport $operationalReport)
    {
        //
    }
}
