<?php

namespace App\Http\Controllers\V2;

use App\Http\Requests\V2\StoreUpdateRequest;
use App\Http\Resources\V2\Update\UpdateResource;
use App\Http\Services\V2\UpdateService;
use App\Models\Report;
use App\Models\Update;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Support\Facades\Auth;

class UpdateController extends Controller
{
    protected $updateService;

    public function __construct(UpdateService $updateService)
    {
        $this->updateService = $updateService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUpdateRequest $request)
    {
        $user = Auth::user();
        $report = Report::find($request->id);

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

    /**
     * Display the specified resource.
     */
    public function show(Update $Update)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
//    public function update(UpdateUpdateRequest $request, Update $Update)
//    {
//        //
//    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Update $Update)
    {
        //
    }
}
