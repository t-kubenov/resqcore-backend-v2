<?php

namespace App\Http\Controllers\V2\Report;

use App\Http\Controllers\V2\Controller;
use App\Http\Requests\V2\Report\FilterReportRequest;
use App\Http\Requests\V2\Report\StoreReportRequest;
use App\Http\Resources\V2\Report\ExportCollection;
use App\Http\Resources\V2\Report\ReportCollection;
use App\Http\Resources\V2\Report\ReportResource;
use App\Http\Services\V2\Report\ReportService;
use App\Models\V2\Report\Report;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Exceptions\ThrottleRequestsException;


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
        $report = Report::findOrFail($id);

        $this->authorize('view', $report);

        $report = $this->reportService->show($report);

        return new ReportResource($report);
    }

    public function store(StoreReportRequest $request)
    {
        $this->authorize('create', Report::class);

        try {
            $report = $this->reportService->storeAndThrottleRequest($request);
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
