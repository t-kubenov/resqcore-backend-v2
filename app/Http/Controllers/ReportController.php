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
use Illuminate\Support\Facades\Auth;
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
        $reports = $this->reportService->index($request);

        return new ReportCollection($reports);
    }

    public function show(Request $request, $id)
    {
        $report = Report::find($id);
        $user = Auth::user();

        if (!$report) {
            return response()->json(['message' => __('reports.show.not-found')], 404);
        }

        if ($user->hasRole('branch_office_user') && $user->branch_id !== $report->branch_office_id) {
            return response()->json(['message' => __('reports.show.not-allowed')], 403);
        }

        $report = $this->reportService->show($report);

        return new ReportResource($report);
    }

    public function store(StoreReportRequest $request)
    {
        $report = $this->reportService->store($request);

        return new ReportResource($report);
    }

    public function destroy($id)
    {
        $deleted = $this->reportService->destroy($id);

        if ($deleted) {
            return response()->json(['message' => __('reports.delete.success')]);
        }

        return response()->json(['message' => __('reports.delete.not-found')], 404);
    }

    public function export(FilterReportRequest $request)
    {
        return new ExportCollection($this->reportService->export($request));
    }
}
