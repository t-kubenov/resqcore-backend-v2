<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Resources\ReportResource;
use App\Http\Resources\ReportCollection;
use App\Http\Requests\StoreReportRequest;
use App\Services\ReportService;
use Illuminate\Support\Facades\Log;



use App\Models\Report;

class ReportController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function index()
    {
        $reports = $this->reportService->paginateReports();
        return new ReportCollection($reports);
    }

    public function show(Request $request, $id)
    {
        $report = $this->reportService->findReportWithDetails($id);

        if ($report) {
            return new ReportResource($report);
        }

        return response()->json(['message' => 'Report not found'], 404);
    }

    public function store(StoreReportRequest $request)
    {
        $attributes = $request['data']['attributes'];
        $report = $this->reportService->createReport($attributes);

        return new ReportResource($report);
    }

    public function update()
    {
        // Update logic here
    }

    public function destroy($id)
    {
        $deleted = $this->reportService->deleteReport($id);

        if ($deleted) {
            return response()->json(['message' => 'Report deleted successfully'], 200);
        }

        return response()->json(['message' => 'Report not found'], 404);
    }
}
