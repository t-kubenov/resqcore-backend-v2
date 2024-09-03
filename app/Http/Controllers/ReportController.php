<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Resources\ReportResource;
use App\Http\Resources\ReportCollection;
use App\Http\Requests\StoreReportRequest;
use Illuminate\Support\Facades\Log;

use App\Models\Operation;

class ReportController extends Controller
{
    public function index()
    {
        return new ReportCollection(Operation::paginate());
    }

    public function show($id)
    {
        $operation = Operation::find($id);

        if ($operation) {
            return new ReportResource($operation);
        }

        return response()->json(['message' => 'Report not found'], 404);
    }

    public function store(StoreReportRequest $request)
    {
        $attributes = $request['data']['attributes'];

        $transport = $attributes['transport'];

        // report code is branch office id and the order of the report from that branch office
        $attributes['report_code'] = $attributes['branch_office_id'] . ' - ' . Operation::where('branch_office_id', $attributes['branch_office_id'])->count()+1;

        $attributes['emergency_type'] = $attributes['emergency_type']['id'];

        $report = Operation::create($attributes);

        if ($transport) {
            $this->parse_transport($report, $transport);
        }

        $data = [];
        $data['id'] = $report->id;
        $data['type'] = 'report';
        $data['attributes'] = $report->toArray();

        return new ReportResource($report, $transport);
    }

    public function update(){
        //
    }

    public function destroy($id)
    {
        $operation = Operation::find($id);
        if ($operation) {
            $operation->delete();
            return response()->json(['message' => 'Operation deleted successfully'], 200);
        }

        return response()->json(['message' => 'Operation not found'], 404);

    }

    private function parse_transport(Operation $report, array $transport)
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
