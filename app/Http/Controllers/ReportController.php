<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Resources\ReportResource;
use App\Http\Resources\ReportCollection;
use App\Http\Requests\StoreReportRequest;
use Illuminate\Support\Facades\Log;

use App\Models\Report;

class ReportController extends Controller
{
    public function index()
    {
        return new ReportCollection(Report::paginate());
    }

    public function show(Request $request, $id)
    {
        $include_updates = $request->query('include_updates');

        $report = Report::find($id);

        if ($report) {
            if ($include_updates) {
                $report->load('updates');
            }

            return new ReportResource($report);
        }

        return response()->json(['message' => 'Report not found'], 404);
    }

    public function store(StoreReportRequest $request)
    {
        $attributes = $request['data']['attributes'];

        $transport = $attributes['transport'];

        // report code is branch office id and the order of the report from that branch office
        $attributes['report_code'] = $attributes['branch_office_id'] . ' - ' . Report::where('branch_office_id', $attributes['branch_office_id'])->count()+1;

        $attributes['emergency_type'] = $attributes['emergency_type']['id'];

        $report = Report::create($attributes);

        if ($transport) {
            $this->parse_transport($report, $transport);
        }

        return new ReportResource($report, $transport);
    }

    public function update(){
        //
    }

    public function destroy($id)
    {
        $report = Report::find($id);
        if ($report) {
            $report->delete();
            return response()->json(['message' => 'Report deleted successfully'], 200);
        }

        return response()->json(['message' => 'Report not found'], 404);

    }

    private function parse_transport(Report $report, array $transport)
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

}
