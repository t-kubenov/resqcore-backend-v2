<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\FilterReportRequest;
use App\Http\Resources\ExportCollection;
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


    public function index(FilterReportRequest $request)
    {
        $reports = $this->reportService->getReports($request);
        return new ReportCollection($reports);
    }


    public function export(FilterReportRequest $request)
    {
        return new ExportCollection($this->reportService->export($request));
    }

    public function show(Request $request, $id)
    {
        return $this->reportService->findReportWithDetails($id);
    }


    public function store(StoreReportRequest $request)
    {
        $attributes = $request['data']['attributes'];

        return $this->reportService->createReport($attributes);
    }


    public function destroy($id)
    {
        $deleted = $this->reportService->deleteReport($id);

        if ($deleted) {
            return response()->json(['message' => __('reports.delete.success')], 200);
        }

        return response()->json(['message' => __('reports.delete.not-found')], 404);
    }
}
