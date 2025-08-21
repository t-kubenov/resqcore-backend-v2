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

    protected $updateService;

    public function __construct(UpdateService $updateService)
    {
        $this->updateService = $updateService;
    }


    public function store(StoreUpdateRequest $request)
    {
        $user = Auth::user();
        $report = Report::find($request->id);

        $this->authorize('create', $report);


        if (!$report) {
            return response()->json(['message' => __('reports.show.not-found')], 404);
        }

        if ($report->is_closed) {
            return response()->json(['message' => __('reports.update.closed')], 400);
        }

        if (!($user->branch_id == $report->branch_office_id || $user->hasRole('admin'))) {
            return response()->json(['message' => __('reports.update.not-allowed')], 403);
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
