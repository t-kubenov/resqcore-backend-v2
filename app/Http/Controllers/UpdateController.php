<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateRequest;
use App\Http\Requests\UpdateUpdateRequest;
use App\Models\Update;
use App\Models\Report;
use App\Http\Resources\UpdateResource;
use App\Services\UpdateService;

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
        $attributes = $request['data']['attributes'];
        $report = Report::find($request->id);

        if (!$report) {
            return response()->json(['message' => 'Report not found'], 404);
        }

        if ($report->is_closed) {
            return response()->json(['message' => 'Report is closed'], 400);
        }

        //TODO: forbid updating if report is from different branch or user

        $update = $this->updateService->createUpdate($report, $attributes);

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
    public function update(UpdateUpdateRequest $request, Update $Update)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Update $Update)
    {
        //
    }
}
