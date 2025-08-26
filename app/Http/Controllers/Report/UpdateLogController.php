<?php

namespace App\Http\Controllers\Report;

use App\Http\Resources\Update\UpdateLogCollection;
use App\Http\Services\UpdateLogService;
use App\Models\V2\Report\Report;

class UpdateLogController
{
    protected UpdateLogService $updateLogService;

    public function __construct(UpdateLogService $updateLogService)
    {
        $this->updateLogService = $updateLogService;
    }

    public function show(int $id)
    {
        $report = Report::find($id);

        if (!$report) {
            return response()->json(['message' => __('reports.show.not-found')], 404);
        }

        return new UpdateLogCollection($this->updateLogService->getUpdateLog($report));
    }
}
