<?php

namespace App\Http\Controllers\V2\Report;

use App\Http\Controllers\V2\Controller;
use App\Http\Requests\V2\Report\StoreUpdateRequest;
use App\Http\Resources\V2\Update\UpdateResource;
use App\Http\Services\V2\Report\UpdateService;
use App\Models\V2\Report\Report;
use App\Models\V2\Report\Update;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Support\Facades\Auth;

class UpdateController extends Controller
{
    use AuthorizesRequests;

    protected UpdateService $updateService;

    public function __construct(UpdateService $updateService)
    {
        $this->updateService = $updateService;
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
