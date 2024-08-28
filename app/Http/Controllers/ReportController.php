<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Resources\ReportResource;
use App\Http\Resources\ReportCollection;
use App\Http\Requests\StoreReportRequest;

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
        return new ReportResource($operation);
    }

    public function store(StoreReportRequest $request)
    {
        $attributes = $request['data']['attributes'];

        // report code is branch office id and the order of the report from that branch office
        $attributes['report_code'] = $attributes['branch_office_id'] . ' - ' . Operation::where('branch_office_id', $attributes['branch_office_id'])->count()+1;

        $report = Operation::create($attributes);

        $data = [];
        $data['id'] = $report->id;
        $data['type'] = 'report';
        $data['attributes'] = $report->toArray();

        return new ReportResource($report);
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

}
