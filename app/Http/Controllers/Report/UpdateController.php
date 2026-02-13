<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Http\Requests\Report\StoreUpdateRequest;
use App\Http\Resources\Update\UpdateCollection;
use App\Http\Resources\Update\UpdateResource;
use App\Http\Services\DocumentFormService;
use App\Http\Services\Report\UpdateService;
use App\Models\Report\Report;
use App\Models\Report\Update;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Exceptions\ThrottleRequestsException;

class UpdateController extends Controller
{
    use AuthorizesRequests;

    protected UpdateService $updateService;

    public function __construct(UpdateService $updateService)
    {
        $this->updateService = $updateService;
    }

    public function index(int $id)
    {
        $report = Report::find($id);

        if (!$report) {
            return response()->json(['message' => __('reports.show.not-found')], 404);
        }

        return new UpdateCollection($this->updateService->getUpdateLog($report));
    }

    public function create(DocumentFormService $service)
    {
        $fields = $service->getCategorizedCreateFields(Update::class);

        return response()->json([
            'categories' => $fields
        ]);
    }

    public function store(StoreUpdateRequest $request)
    {
        $report = Report::findOrFail($request->id);

        $this->authorize('create', [Update::class, $report]);

        if ($report->is_closed) {
            return response()->json(['message' => __('reports.update.closed')], 400);
        }

        if (isset($request['data']['attributes']['close_date']) &&
            $report->work_start_date > $request['data']['attributes']['close_date']) {
            return response()->json(['message' => __('reports.update.invalid-close-date')], 422);
        }

        $attributes = $request['data']['attributes'];

        try {
            $update = $this->updateService->updateRequest($request, $report, $attributes);
        }
        catch (ThrottleRequestsException $e) {
            return response()->json(['message' => __('throttle.update-create')], 429);
        }

        return new UpdateResource($update);
    }
}
