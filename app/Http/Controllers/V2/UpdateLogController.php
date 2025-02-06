<?php

namespace App\Http\Controllers\V2;

use App\Http\Resources\V2\Update\UpdateLogCollection;
use App\Http\Services\V2\UpdateLogService;
use App\Models\Report;

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
