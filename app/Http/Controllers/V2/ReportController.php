<?php

namespace App\Http\Controllers\V2;

use App\Http\Requests\V2\FilterReportRequest;
use App\Http\Requests\V2\StoreReportRequest;
use App\Http\Resources\V2\ReportCollection;
use App\Http\Resources\V2\ReportResource;
use App\Http\Resources\ExportCollection;
use App\Models\Report;
use App\Services\V2\ReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


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
