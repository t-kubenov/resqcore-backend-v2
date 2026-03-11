<?php

namespace App\Report\Http;

use App\Document\DocumentFormService;
use App\Report\Report;
use App\Report\ReportService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Routing\Controller;
use App\Report\V2\ReportResource as ReportResourceV2;
use App\Report\V2\ReportCollection as ReportCollectionV2;

class ReportController extends Controller
{
    use AuthorizesRequests;

    public function __construct(protected ReportService $reportService){}

    public function index_v2(FilterReportRequest $request)
    {
        $reports = $this->reportService->index($request);

        return new ReportCollectionV2($reports);
    }

    public function index(FilterReportRequest $request)
    {
        $reports = $this->reportService->index($request);

        return new ReportCollection($reports);
    }

    public function show_v2(int $id)
    {
        $report = Report::findOrFail($id);

        $this->authorize('view', $report);

        $report = $this->reportService->show($report);

        return new ReportResourceV2($report);
    }

    public function show(int $id)
    {
        $report = Report::findOrFail($id);

        $this->authorize('view', $report);

        $report = $this->reportService->show($report);

        return new ReportResource($report);
    }

    public function create(DocumentFormService $service)
    {
        $fields = $service->getCategorizedCreateFields(Report::class);

        return response()->json([
            'categories' => $fields
        ]);
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
