<?php

namespace App\Http\Controllers\V2;

use App\Http\Requests\V2\FilterReportRequest;
use App\Http\Requests\V2\StoreReportRequest;
use App\Http\Resources\V2\Report\ExportCollection;
use App\Http\Resources\V2\Report\ReportCollection;
use App\Http\Resources\V2\Report\ReportResource;
use App\Http\Services\V2\ReportService;
use App\Models\Report;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Support\Facades\Auth;


class ReportController extends Controller
{
    use AuthorizesRequests;

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

    public function show(int $id)
    {
        $report = Report::find($id);

        if (!$report) {
            return response()->json(['message' => __('reports.show.not-found')], 404);
        }

        $this->authorize('show', $report);

        $report = $this->reportService->show($report);

        return new ReportResource($report);
    }

    public function store(StoreReportRequest $request)
    {
        $this->authorize('create', Report::class);

        try {
            $report = $this->reportService->storeRequest($request);
        }
        catch (ThrottleRequestsException $e) {
            return response()->json(['message' => __('throttle.report-create')], 429);
        }

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
