<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Http\Requests\Report\FilterReportRequest;
use App\Http\Requests\Report\StoreReportRequest;
use App\Http\Resources\Report\ExportCollection;
use App\Http\Resources\Report\ReportCollection;
use App\Http\Resources\Report\ReportResource;
use App\Http\Services\DocumentFormService;
use App\Http\Services\Report\ReportService;
use App\Models\Report\Report;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Exceptions\ThrottleRequestsException;


class ReportController extends Controller
{
    use AuthorizesRequests;

    public function __construct(protected ReportService $reportService){}

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

    public function create(DocumentFormService $service)
    {
        $fields = $service->getCategorizedCreateFields(Report::class);

        return response()->json([
            'fields' => $fields
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
